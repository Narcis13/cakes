# Implementation Plan: Step 3 Calendar Redesign

## Overview

Replace the basic HTML5 date input in Step 3 with a 31-day interactive calendar featuring week navigation, availability indicators, and a sticky summary bar. Implementation uses the detailed spec at `specs/jolly-whistling-meerkat.md`.

## Phase 1: Backend API

Create new endpoint for fetching 31 days of calendar availability data.

### Tasks

- [ ] Add route for `get-calendar-availability` endpoint
- [ ] Implement `getCalendarAvailability()` method in AvailabilityService
- [ ] Implement `getCalendarAvailability()` action in AppointmentsController
- [ ] Update authentication and CSRF configuration

### Technical Details

**Route configuration** (`config/routes.php`, after line 44):
```php
$builder->connect('/appointments/get-calendar-availability',
    ['controller' => 'Appointments', 'action' => 'getCalendarAvailability']);
```

**AvailabilityService method** (`src/Service/AvailabilityService.php`):
```php
public function getCalendarAvailability(
    int $doctorId,
    int $serviceId,
    string $startDate,
    int $days = 31
): array {
    $calendar = [];
    $currentDate = new \DateTime($startDate);
    $today = new \DateTime();
    $today->setTime(0, 0, 0);
    $firstAvailable = null;

    for ($i = 0; $i < $days; $i++) {
        $dateStr = $currentDate->format('Y-m-d');
        $dayOfWeek = (int)$currentDate->format('N');

        // Check past
        if ($currentDate < $today) {
            $calendar[$dateStr] = ['status' => 'past', 'slots_count' => 0, 'available_count' => 0, 'label' => null];
            $currentDate->modify('+1 day');
            continue;
        }

        // Check weekend
        if (in_array($dayOfWeek, [6, 7]) && !Configure::read('Appointments.allow_weekend_appointments', false)) {
            $calendar[$dateStr] = ['status' => 'weekend', 'slots_count' => 0, 'available_count' => 0, 'label' => null];
            $currentDate->modify('+1 day');
            continue;
        }

        // Check hospital holiday
        $holiday = $this->hospitalHolidaysTable->find()
            ->where(['date' => $dateStr])
            ->first();
        if ($holiday) {
            $calendar[$dateStr] = ['status' => 'holiday', 'slots_count' => 0, 'available_count' => 0, 'label' => $holiday->name];
            $currentDate->modify('+1 day');
            continue;
        }

        // Check staff unavailability
        $unavailability = $this->staffUnavailabilitiesTable->find()
            ->where([
                'staff_id' => $doctorId,
                'date_from <=' => $dateStr,
                'date_to >=' => $dateStr
            ])
            ->first();
        if ($unavailability) {
            $calendar[$dateStr] = ['status' => 'unavailable', 'slots_count' => 0, 'available_count' => 0, 'label' => $unavailability->reason ?? 'Indisponibil'];
            $currentDate->modify('+1 day');
            continue;
        }

        // Get slot counts
        $slots = $this->getAvailableSlots($doctorId, $dateStr, $serviceId);
        $totalCount = count($slots);
        $availableCount = count(array_filter($slots, fn($s) => $s['available']));

        if ($totalCount === 0) {
            $status = 'unavailable';
        } elseif ($availableCount === 0) {
            $status = 'full';
        } elseif ($availableCount <= $totalCount * 0.3) {
            $status = 'partial';
        } else {
            $status = 'available';
        }

        if (!$firstAvailable && ($status === 'available' || $status === 'partial')) {
            $firstAvailable = $dateStr;
        }

        $calendar[$dateStr] = [
            'status' => $status,
            'slots_count' => $totalCount,
            'available_count' => $availableCount,
            'label' => null
        ];

        $currentDate->modify('+1 day');
    }

    return ['calendar' => $calendar, 'first_available_date' => $firstAvailable];
}
```

**Controller action** (`src/Controller/AppointmentsController.php`):
```php
public function getCalendarAvailability(): ?Response
{
    $this->request->allowMethod(['post', 'ajax']);

    $doctorId = (int)$this->request->getData('doctor_id');
    $serviceId = (int)$this->request->getData('service_id');
    $startDate = $this->request->getData('start_date', date('Y-m-d'));
    $days = min((int)$this->request->getData('days', 31), 31);

    if (!$doctorId || !$serviceId) {
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Doctor și serviciu obligatorii'
            ]));
    }

    $availabilityService = new AvailabilityService();
    $result = $availabilityService->getCalendarAvailability($doctorId, $serviceId, $startDate, $days);

    return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'success' => true,
            'calendar' => $result['calendar'],
            'first_available_date' => $result['first_available_date']
        ]));
}
```

**Authentication config** (`src/Controller/AppointmentsController.php`):
- Add `'getCalendarAvailability'` to `allowUnauthenticated` array (line 62)
- Add `'getCalendarAvailability'` to `unlockedActions` array (line 71)

**API Response Format**:
```json
{
  "success": true,
  "calendar": {
    "2026-01-15": {"status": "available", "slots_count": 8, "available_count": 5, "label": null},
    "2026-01-16": {"status": "holiday", "slots_count": 0, "available_count": 0, "label": "Ziua Unirii"},
    "2026-01-17": {"status": "weekend", "slots_count": 0, "available_count": 0, "label": null}
  },
  "first_available_date": "2026-01-15"
}
```

---

## Phase 2: Frontend HTML Structure

Replace Step 3 HTML with new calendar-based layout.

### Tasks

- [ ] Replace Step 3 HTML structure (lines 178-254) with two-panel layout
- [ ] Add calendar panel with week navigation, day grid, quick nav, legend
- [ ] Add time slots panel with loading, empty, and grid states
- [ ] Add sticky summary bar outside the form-step div
- [ ] Update hidden form fields for date and time

### Technical Details

**File**: `templates/Appointments/index.php`

**Step 3 HTML replacement** (lines 178-254):
See `specs/jolly-whistling-meerkat.md` section 3.1 for complete HTML structure.

Key elements:
- `.date-time-selection-v2` - Grid container (380px calendar | 1fr slots)
- `.calendar-panel` - Week navigation + day grid + legend
- `.time-slots-panel` - Slots header + container with states
- `#booking-sticky-bar` - Fixed summary bar (placed after form-step)

**Hidden fields**:
```php
<?= $this->Form->hidden('appointment_date', ['id' => 'appointment-date']) ?>
<?= $this->Form->hidden('appointment_time', ['id' => 'selected-time']) ?>
```

**Sticky bar placement**: After line 382 (before closing container div), outside the `form-step` div.

---

## Phase 3: Frontend CSS Styles

Add all styling for calendar, time slots, and sticky bar components.

### Tasks

- [ ] Add calendar panel styles (layout, navigation, day cells, status colors)
- [ ] Add time slots panel styles (header, grid, slot states)
- [ ] Add sticky summary bar styles (fixed positioning, dark theme)
- [ ] Add responsive breakpoints for mobile (< 900px and < 480px)

### Technical Details

**File**: `templates/Appointments/index.php` (within `<style>` block, after line 1507)

**CSS classes to add** (~600 lines total):
See `specs/jolly-whistling-meerkat.md` section 3.2 for complete CSS.

**Key class prefixes** (use `-v2` suffix to avoid conflicts):
- `.date-time-selection-v2`
- `.calendar-panel`, `.calendar-header`, `.calendar-days`, `.calendar-day`
- `.time-slots-panel`, `.time-slots-grid-v2`, `.time-slot-v2`
- `.booking-sticky-bar`, `.sticky-bar-content`, `.sticky-item`

**Status color scheme**:
- Available: `#ecfdf5` bg, `#10b981` indicator
- Partial: `#fef9c3` bg, `#eab308` indicator
- Full: `#f1f5f9` bg, `#cbd5e1` indicator
- Holiday: `#fef2f2` bg, `#ef4444` indicator
- Weekend: `#f8fafc` bg, `#e2e8f0` indicator
- Selected: `linear-gradient(135deg, #3b82f6, #1d4ed8)` bg

**Responsive breakpoints**:
- `@media (max-width: 900px)`: Single column layout
- `@media (max-width: 480px)`: Compact sticky bar, smaller day cells

---

## Phase 4: Frontend JavaScript Logic [complex]

Implement the `AppointmentCalendar` class and integrate with existing booking flow.

### Tasks

- [ ] Implement `AppointmentCalendar` class
  - [ ] Add `loadCalendarData()` method for AJAX calendar fetch
  - [ ] Add `renderWeek()` method for week view rendering
  - [ ] Add `selectDate()` method for date selection and slot loading
  - [ ] Add `selectSlot()` method for time slot selection
  - [ ] Add week navigation methods (prev/next/goto)
  - [ ] Add sticky bar update methods
- [ ] Integrate with existing step navigation
- [ ] Wire up event listeners for calendar interactions

### Technical Details

**File**: `templates/Appointments/index.php` (within `<script>` block, after line 2152)

**AppointmentCalendar class** (~450 lines):
See `specs/jolly-whistling-meerkat.md` section 3.3 for complete JavaScript.

**Class structure**:
```javascript
class AppointmentCalendar {
    constructor(options) {
        this.doctorId = options.doctorId;
        this.serviceId = options.serviceId;
        this.doctorName = options.doctorName || '';
        this.currentWeek = 0;  // 0-4
        this.totalWeeks = 5;
        this.selectedDate = null;
        this.selectedTime = null;
        this.calendarData = {};
        this.firstAvailableDate = null;
        this.startDate = new Date();
        this.init();
    }

    async init() { /* Load data and render */ }
    async loadCalendarData() { /* AJAX call to get-calendar-availability */ }
    renderWeek(weekIndex) { /* Render 7-day week view */ }
    updateWeekDisplay(weekStart) { /* Update month/year header */ }
    updateWeekNavigation() { /* Enable/disable nav buttons */ }
    updateWeekIndicator() { /* Update week dots */ }
    goToWeek(weekIndex) { /* Navigate to specific week */ }
    selectDate(dateStr) { /* Select date, load time slots */ }
    async loadTimeSlots(dateStr) { /* AJAX call to get-available-slots */ }
    renderTimeSlots(slots) { /* Render slot grid with animation */ }
    selectSlot(element, time) { /* Select time slot, update state */ }
    updateStickyBar() { /* Update sticky bar content */ }
    goToNextAvailable() { /* Find and select next available date */ }
}
```

**Integration with `goToStep()`** (modify existing function around line 1899):
```javascript
function goToStep(step) {
    // ... existing code ...

    // Initialize calendar when entering Step 3
    if (step === 3 && bookingData.doctorId && bookingData.serviceId) {
        if (!window.appointmentCalendar) {
            window.appointmentCalendar = new AppointmentCalendar({
                doctorId: bookingData.doctorId,
                serviceId: bookingData.serviceId,
                doctorName: bookingData.doctorName
            });
        }
        document.getElementById('booking-sticky-bar')?.classList.add('visible');
    } else {
        document.getElementById('booking-sticky-bar')?.classList.remove('visible');
    }
}
```

**AJAX calls use existing `getCsrfToken()` function** (line 2141).

---

## Phase 5: Integration & Polish

Final integration testing and refinements.

### Tasks

- [ ] Verify complete booking flow (Step 1 through Step 5)
- [ ] Test calendar navigation (all 5 weeks)
- [ ] Test edge cases (weekends, holidays, full days)
- [ ] Test sticky bar behavior (show/hide, button enable/disable)
- [ ] Test mobile responsive layout
- [ ] Clear cache and verify in production mode

### Technical Details

**Test scenarios**:
1. Fresh booking: Select specialty → doctor → date from calendar → time → complete
2. Weekend handling: Weekend days should be gray and not clickable
3. Holiday handling: Holiday days show name on hover, not clickable
4. Full day handling: Days with no available slots are gray
5. Quick navigation: "Prima zi disponibilă" jumps to correct week
6. Sticky bar: Updates on date selection, again on time selection
7. Mobile: Single column layout, touch interactions work

**Cache clear command**:
```bash
bin/cake cache clear_all
```

**Start dev server**:
```bash
bin/cake server -p 8765
```

**Verification URL**: `http://localhost:8765/appointments` (requires patient login)
