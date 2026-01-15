# Plan: Redesign Step 3 - Selecția Datei și Orei (Appointment Booking)

## Rezumat Executiv

Redesign complet al pasului 3 din fluxul de programare online, transformând selectorul simplu de dată într-un **calendar interactiv de 31 de zile** cu vizualizare clară a disponibilității, care să permită pacienților să aleagă rapid și intuitiv un slot disponibil.

---

## 1. Design Concept - Calendar Visual de 31 Zile

### 1.1 Layout General

```
┌─────────────────────────────────────────────────────────────────────────────┐
│  PASUL 3: SELECTAȚI DATA ȘI ORA                                             │
│  ─────────────────────────────────────────────────────────────────────────  │
│                                                                             │
│  ┌─────────────────────────────────────────────────────────────────────┐   │
│  │  🩺 Dr. Maria Popescu  |  💉 Consultație Cardiologie  |  ⏱️ 30 min  │   │
│  └─────────────────────────────────────────────────────────────────────┘   │
│                                                                             │
│  ┌────────────────────────────────┐  ┌───────────────────────────────┐    │
│  │  ◀  Săptămâna 15-21 Ian  ▶    │  │      SLOTURI DISPONIBILE      │    │
│  │                                │  │                               │    │
│  │   L     M     M     J     V    │  │  ┌────┐ ┌────┐ ┌────┐ ┌────┐ │    │
│  │ ┌───┐ ┌───┐ ┌───┐ ┌───┐ ┌───┐ │  │  │9:00│ │9:30│ │10:00│ │10:30││    │
│  │ │ 15│ │ 16│ │ 17│ │ 18│ │ 19│ │  │  └────┘ └────┘ └────┘ └────┘ │    │
│  │ │ 🟢│ │ 🟢│ │ 🟡│ │ 🔴│ │ 🟢│ │  │  ┌────┐ ┌────┐ ┌────┐ ┌────┐ │    │
│  │ └───┘ └───┘ └───┘ └───┘ └───┘ │  │  │11:00│ │11:30│ │14:00│ │14:30││    │
│  │   S     D                      │  │  └────┘ └────┘ └────┘ └────┘ │    │
│  │ ┌───┐ ┌───┐                    │  │                               │    │
│  │ │ 20│ │ 21│    Săpt. 1/5       │  │  🟢 Liber  ⬛ Ocupat          │    │
│  │ │ ⬜│ │ ⬜│                     │  │                               │    │
│  │ └───┘ └───┘                    │  └───────────────────────────────┘    │
│  │                                │                                        │
│  │  🟢 Disponibil  🟡 Parțial     │                                        │
│  │  ⬛ Ocupat  🔴 Sărbătoare      │                                        │
│  │  ⬜ Weekend                    │                                        │
│  └────────────────────────────────┘                                        │
│                                                                             │
│  [◀ Pasul Anterior]                              [Următorul Pas ▶]         │
│                                                                             │
├─────────────────────────────────────────────────────────────────────────────┤
│  📋 SUMAR PROGRAMARE (STICKY BAR)                                          │
│  ─────────────────────────────────────────────────────────────────────────  │
│  🩺 Dr. Maria Popescu  │  📅 Joi, 18 Ianuarie  │  🕐 10:00  │  [Continuă ▶]│
└─────────────────────────────────────────────────────────────────────────────┘
```

### 1.2 Caracteristici UI Principale

| Element | Descriere | Beneficiu |
|---------|-----------|-----------|
| **Calendar Săptămânal** | Navigare pe săptămâni (◀ ▶), 5 săptămâni în total | Vizualizare clară, focus pe săptămâna curentă |
| **Ziua Selectată** | Highlight puternic cu border și shadow | Feedback vizual clar |
| **Time Slots Grid** | Grid 4 coloane pe desktop, 2 pe mobil, toate sloturile vizibile | Selecție rapidă, vizibilitate completă program |
| **Quick Nav** | Buton "Prima zi disponibilă" | Economie de timp |
| **Sticky Summary Bar** | Bară fixă în jos cu: Medic, Data, Ora, buton Continuă | Vizibilitate constantă a selecțiilor |
| **Tooltips** | Hover pe zile indisponibile arată motivul | Transparență |
| **Animații** | Fade-in pentru slots, scale pentru selecție | UX premium |

---

## 2. Structura Datelor și API

### 2.1 Endpoint Nou: `getCalendarAvailability`

**Request:**
```json
POST /appointments/get-calendar-availability
{
    "doctor_id": 5,
    "service_id": 12,
    "start_date": "2026-01-15",
    "days": 31
}
```

**Response:**
```json
{
    "success": true,
    "calendar": {
        "2026-01-15": {
            "status": "available",      // available | partial | full | holiday | unavailable | weekend
            "slots_count": 8,
            "available_count": 5,
            "label": null               // null sau "Crăciun" pentru sărbători
        },
        "2026-01-16": {
            "status": "holiday",
            "slots_count": 0,
            "available_count": 0,
            "label": "Ziua Unirii"
        },
        // ... restul zilelor
    },
    "first_available_date": "2026-01-15",
    "doctor_schedule": {
        "working_days": [1, 2, 3, 4, 5],  // Luni-Vineri
        "typical_hours": "09:00 - 17:00"
    }
}
```

### 2.2 Logic Backend pentru Calendar

```php
// În AvailabilityService.php - metodă nouă
public function getCalendarAvailability(
    int $doctorId,
    int $serviceId,
    string $startDate,
    int $days = 31
): array {
    $calendar = [];
    $currentDate = new DateTime($startDate);

    for ($i = 0; $i < $days; $i++) {
        $dateStr = $currentDate->format('Y-m-d');
        $dayOfWeek = (int)$currentDate->format('N');

        // 1. Verifică weekend
        if (in_array($dayOfWeek, [6, 7])) {
            $calendar[$dateStr] = ['status' => 'weekend', ...];
            continue;
        }

        // 2. Verifică sărbători spital
        if ($this->isHospitalHoliday($dateStr)) {
            $calendar[$dateStr] = ['status' => 'holiday', 'label' => $holidayName];
            continue;
        }

        // 3. Verifică indisponibilitate medic
        if ($this->isDoctorUnavailable($doctorId, $dateStr)) {
            $calendar[$dateStr] = ['status' => 'unavailable', ...];
            continue;
        }

        // 4. Calculează sloturi disponibile
        $slots = $this->getAvailableSlots($doctorId, $dateStr, $serviceId);
        $availableCount = count(array_filter($slots, fn($s) => $s['available']));
        $totalCount = count($slots);

        if ($availableCount === 0) {
            $status = 'full';
        } elseif ($availableCount < $totalCount / 2) {
            $status = 'partial';
        } else {
            $status = 'available';
        }

        $calendar[$dateStr] = [
            'status' => $status,
            'slots_count' => $totalCount,
            'available_count' => $availableCount,
            'label' => null
        ];

        $currentDate->modify('+1 day');
    }

    return $calendar;
}
```

---

## 3. Implementare Frontend

### 3.1 HTML Structure (templates/Appointments/index.php - Step 3)

```html
<!-- Step 3: Select Date and Time - REDESIGNED -->
<div class="form-step" data-step="3">
    <div class="step-header">
        <h2><i class="fas fa-calendar-check"></i> Selectați Data și Ora</h2>
        <p class="step-description">
            Alegeți o dată disponibilă din calendar și apoi selectați ora dorită.
        </p>
    </div>

    <!-- Selected Info Card (existing) -->
    <div class="selected-info-card">...</div>

    <!-- NEW: Calendar + Slots Container -->
    <div class="date-time-selection-v2">

        <!-- LEFT: Calendar Panel -->
        <div class="calendar-panel">
            <div class="calendar-header">
                <button type="button" class="calendar-nav-btn" id="prev-month" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="calendar-title">
                    <span id="calendar-month-year">Ianuarie 2026</span>
                </div>
                <button type="button" class="calendar-nav-btn" id="next-month">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="calendar-weekdays">
                <span>L</span><span>M</span><span>M</span><span>J</span>
                <span>V</span><span>S</span><span>D</span>
            </div>

            <div id="calendar-days" class="calendar-days">
                <!-- Days generated by JavaScript -->
            </div>

            <!-- Quick Navigation -->
            <div class="calendar-quick-nav">
                <button type="button" class="btn-quick-nav" id="goto-first-available">
                    <i class="fas fa-bolt"></i> Prima zi disponibilă
                </button>
            </div>

            <!-- Legend -->
            <div class="calendar-legend">
                <div class="legend-item">
                    <span class="legend-dot available"></span>
                    <span>Disponibil</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot partial"></span>
                    <span>Parțial ocupat</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot full"></span>
                    <span>Complet ocupat</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot holiday"></span>
                    <span>Sărbătoare</span>
                </div>
                <div class="legend-item">
                    <span class="legend-dot weekend"></span>
                    <span>Weekend</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: Time Slots Panel -->
        <div class="time-slots-panel">
            <div class="time-slots-header">
                <h4><i class="fas fa-clock"></i> Ore Disponibile</h4>
                <span id="selected-date-display" class="selected-date-badge">
                    Selectați o dată
                </span>
            </div>

            <div id="time-slots-container-v2">
                <!-- Initial State -->
                <div id="slots-placeholder" class="slots-placeholder">
                    <i class="fas fa-hand-pointer fa-3x"></i>
                    <p>Selectați o dată din calendar pentru a vedea orele disponibile</p>
                </div>

                <!-- Loading State -->
                <div id="slots-loading-v2" class="slots-loading" style="display: none;">
                    <div class="spinner-wrapper">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>Se încarcă orele...</p>
                    </div>
                </div>

                <!-- No Slots State -->
                <div id="no-slots-v2" class="no-slots-message" style="display: none;">
                    <i class="fas fa-calendar-times fa-3x"></i>
                    <p>Nu sunt ore disponibile pentru această zi.</p>
                    <button type="button" class="btn-suggestion" id="suggest-next-day">
                        <i class="fas fa-arrow-right"></i> Următoarea zi disponibilă
                    </button>
                </div>

                <!-- Time Slots Grid -->
                <div id="time-slots-grid-v2" class="time-slots-grid-v2" style="display: none;">
                    <!-- Slots generated by JavaScript -->
                </div>
            </div>

            <!-- Slots Legend -->
            <div id="slots-legend-v2" class="slots-legend" style="display: none;">
                <div class="legend-item">
                    <div class="legend-slot available"></div>
                    <span>Disponibil</span>
                </div>
                <div class="legend-item">
                    <div class="legend-slot selected"></div>
                    <span>Selectat</span>
                </div>
                <div class="legend-item">
                    <div class="legend-slot occupied"></div>
                    <span>Ocupat</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Fields -->
    <?= $this->Form->hidden('appointment_date', ['id' => 'appointment-date']) ?>
    <?= $this->Form->hidden('appointment_time', ['id' => 'selected-time']) ?>

    <!-- Step Actions (above sticky bar) -->
    <div class="step-actions">
        <button type="button" class="btn btn-secondary prev-step">
            <i class="fas fa-arrow-left"></i> Pasul Anterior
        </button>
        <button type="button" class="btn btn-primary next-step" disabled>
            Următorul Pas <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>

<!-- STICKY SUMMARY BAR (outside form-step, fixed to bottom) -->
<div id="booking-sticky-bar" class="booking-sticky-bar" style="display: none;">
    <div class="sticky-bar-content">
        <div class="sticky-bar-items">
            <div class="sticky-item">
                <i class="fas fa-user-md"></i>
                <div class="sticky-item-content">
                    <span class="sticky-label">Medic</span>
                    <span class="sticky-value" id="sticky-doctor">-</span>
                </div>
            </div>
            <div class="sticky-divider"></div>
            <div class="sticky-item">
                <i class="fas fa-calendar-day"></i>
                <div class="sticky-item-content">
                    <span class="sticky-label">Data</span>
                    <span class="sticky-value" id="sticky-date">Selectați data</span>
                </div>
            </div>
            <div class="sticky-divider"></div>
            <div class="sticky-item">
                <i class="fas fa-clock"></i>
                <div class="sticky-item-content">
                    <span class="sticky-label">Ora</span>
                    <span class="sticky-value" id="sticky-time">Selectați ora</span>
                </div>
            </div>
        </div>
        <button type="button" class="btn-sticky-continue" id="sticky-continue" disabled>
            Continuă <i class="fas fa-arrow-right"></i>
        </button>
    </div>
</div>
```

### 3.2 CSS Styles

```css
/* ===== CALENDAR PANEL STYLES ===== */
.date-time-selection-v2 {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 30px;
    align-items: start;
}

.calendar-panel {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
}

.calendar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.calendar-nav-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background: #fff;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.calendar-nav-btn:hover:not(:disabled) {
    background: #3b82f6;
    color: #fff;
    border-color: #3b82f6;
}

.calendar-nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.calendar-title {
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-bottom: 8px;
    text-align: center;
}

.calendar-weekdays span {
    font-size: 12px;
    font-weight: 600;
    color: #94a3b8;
    padding: 8px 0;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

/* Calendar Day Cell */
.calendar-day {
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
    font-weight: 500;
    font-size: 14px;
}

.calendar-day .day-number {
    font-weight: 600;
    line-height: 1;
}

.calendar-day .day-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    margin-top: 4px;
}

/* Day Status Styles */
.calendar-day.available {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.calendar-day.available:hover {
    background: #d1fae5;
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}

.calendar-day.available .day-indicator {
    background: #10b981;
}

.calendar-day.partial {
    background: #fef9c3;
    color: #a16207;
    border: 1px solid #fde047;
}

.calendar-day.partial:hover {
    background: #fef08a;
    transform: scale(1.05);
}

.calendar-day.partial .day-indicator {
    background: #eab308;
}

.calendar-day.full {
    background: #f1f5f9;
    color: #94a3b8;
    border: 1px solid #e2e8f0;
    cursor: not-allowed;
}

.calendar-day.full .day-indicator {
    background: #cbd5e1;
}

.calendar-day.holiday {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    cursor: not-allowed;
}

.calendar-day.holiday .day-indicator {
    background: #ef4444;
}

.calendar-day.weekend {
    background: #f8fafc;
    color: #cbd5e1;
    border: 1px solid #f1f5f9;
    cursor: not-allowed;
}

.calendar-day.unavailable {
    background: #fef2f2;
    color: #fca5a5;
    border: 1px solid #fecaca;
    cursor: not-allowed;
}

.calendar-day.selected {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
    color: #fff !important;
    border: 2px solid #1d4ed8 !important;
    transform: scale(1.08);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.calendar-day.selected .day-indicator {
    background: #fff !important;
}

.calendar-day.today {
    border: 2px solid #3b82f6;
}

.calendar-day.today::after {
    content: 'Azi';
    position: absolute;
    top: 2px;
    right: 2px;
    font-size: 8px;
    font-weight: 700;
    color: #3b82f6;
    text-transform: uppercase;
}

.calendar-day.past {
    opacity: 0.4;
    cursor: not-allowed;
}

/* Quick Navigation */
.calendar-quick-nav {
    margin-top: 16px;
    text-align: center;
}

.btn-quick-nav {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-quick-nav:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

/* Calendar Legend */
.calendar-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.calendar-legend .legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    color: #64748b;
}

.legend-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.legend-dot.available { background: #10b981; }
.legend-dot.partial { background: #eab308; }
.legend-dot.full { background: #cbd5e1; }
.legend-dot.holiday { background: #ef4444; }
.legend-dot.weekend { background: #e2e8f0; }

/* ===== TIME SLOTS PANEL STYLES ===== */
.time-slots-panel {
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid #e2e8f0;
    min-height: 400px;
}

.time-slots-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e2e8f0;
}

.time-slots-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 10px;
}

.time-slots-header h4 i {
    color: #3b82f6;
}

.selected-date-badge {
    background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
    color: #0284c7;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.slots-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    color: #94a3b8;
    text-align: center;
}

.slots-placeholder i {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.slots-placeholder p {
    font-size: 15px;
    max-width: 280px;
}

.time-slots-grid-v2 {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
}

/* Time Slot Styles */
.time-slot-v2 {
    padding: 14px 8px;
    text-align: center;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
    position: relative;
}

.time-slot-v2:hover:not(.occupied) {
    border-color: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.time-slot-v2.selected {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #fff;
    border-color: #1d4ed8;
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
}

.time-slot-v2.selected::after {
    content: '\f00c';
    font-family: 'Font Awesome 6 Free';
    font-weight: 900;
    position: absolute;
    top: 4px;
    right: 6px;
    font-size: 10px;
}

.time-slot-v2.occupied {
    background: #f8fafc;
    color: #cbd5e1;
    border-color: #e2e8f0;
    cursor: not-allowed;
    text-decoration: line-through;
}

.time-slot-v2 .slot-time {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.2;
}

.time-slot-v2 .slot-period {
    font-size: 11px;
    text-transform: uppercase;
    opacity: 0.8;
    margin-top: 2px;
}

/* Slots Legend */
.slots-legend {
    display: flex;
    gap: 20px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid #e2e8f0;
}

.slots-legend .legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #64748b;
}

.legend-slot {
    width: 28px;
    height: 20px;
    border-radius: 6px;
    border: 2px solid;
}

.legend-slot.available {
    background: #fff;
    border-color: #e2e8f0;
}

.legend-slot.selected {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    border-color: #1d4ed8;
}

.legend-slot.occupied {
    background: #f8fafc;
    border-color: #e2e8f0;
}

/* ===== STICKY SUMMARY BAR STYLES ===== */
.booking-sticky-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    padding: 16px 20px;
    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.booking-sticky-bar.visible {
    transform: translateY(0);
}

.sticky-bar-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.sticky-bar-items {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}

.sticky-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff;
}

.sticky-item i {
    font-size: 24px;
    color: #3b82f6;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(59, 130, 246, 0.15);
    border-radius: 10px;
}

.sticky-item-content {
    display: flex;
    flex-direction: column;
}

.sticky-label {
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.sticky-value {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
}

.sticky-divider {
    width: 1px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
}

.btn-sticky-continue {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    border: none;
    padding: 14px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.btn-sticky-continue:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.btn-sticky-continue:disabled {
    background: #475569;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Week Navigation Indicator */
.week-indicator {
    text-align: center;
    margin-top: 12px;
    font-size: 13px;
    color: #64748b;
}

.week-dots {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 8px;
}

.week-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
}

.week-dot.active {
    background: #3b82f6;
    transform: scale(1.2);
}

.week-dot:hover {
    background: #94a3b8;
}

/* Mobile Responsive */
@media (max-width: 900px) {
    .date-time-selection-v2 {
        grid-template-columns: 1fr;
    }

    .calendar-panel {
        max-width: 100%;
    }

    .time-slots-grid-v2 {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    }

    .sticky-bar-items {
        flex-wrap: wrap;
        gap: 12px;
    }

    .sticky-divider {
        display: none;
    }
}

@media (max-width: 480px) {
    .calendar-day {
        font-size: 12px;
    }

    .calendar-legend {
        justify-content: center;
    }

    .time-slots-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .booking-sticky-bar {
        padding: 12px 16px;
    }

    .sticky-bar-content {
        flex-direction: column;
        gap: 12px;
    }

    .sticky-bar-items {
        width: 100%;
        justify-content: space-around;
    }

    .sticky-item i {
        width: 32px;
        height: 32px;
        font-size: 18px;
    }

    .sticky-value {
        font-size: 13px;
    }

    .btn-sticky-continue {
        width: 100%;
        justify-content: center;
    }
}
```

### 3.3 JavaScript Logic

```javascript
// Step 3 Calendar Logic - Week Navigation Implementation
class AppointmentCalendar {
    constructor(options) {
        this.doctorId = options.doctorId;
        this.serviceId = options.serviceId;
        this.doctorName = options.doctorName || '';
        this.currentWeek = 0;  // 0-4 (5 weeks total)
        this.totalWeeks = 5;
        this.selectedDate = null;
        this.selectedTime = null;
        this.calendarData = {};
        this.firstAvailableDate = null;
        this.startDate = new Date();
        this.startDate.setHours(0, 0, 0, 0);

        this.init();
    }

    async init() {
        await this.loadCalendarData();
        this.render();
        this.attachEventListeners();
    }

    async loadCalendarData() {
        const startDate = new Date();
        startDate.setHours(0, 0, 0, 0);

        try {
            const response = await fetch('/appointments/get-calendar-availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({
                    doctor_id: this.doctorId,
                    service_id: this.serviceId,
                    start_date: startDate.toISOString().split('T')[0],
                    days: 31
                })
            });

            const data = await response.json();

            if (data.success) {
                this.calendarData = data.calendar;
                this.firstAvailableDate = data.first_available_date;
            }
        } catch (error) {
            console.error('Error loading calendar:', error);
        }
    }

    render() {
        this.renderWeek(this.currentWeek);
        this.updateWeekNavigation();
        this.updateWeekIndicator();
    }

    renderWeek(weekIndex) {
        const container = document.getElementById('calendar-days');
        container.innerHTML = '';

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Calculate week start date
        const weekStart = new Date(this.startDate);
        weekStart.setDate(weekStart.getDate() + (weekIndex * 7));

        // Adjust to Monday of that week
        const dayOfWeek = weekStart.getDay() || 7;
        if (dayOfWeek !== 1) {
            weekStart.setDate(weekStart.getDate() - (dayOfWeek - 1));
        }

        // Render 7 days for this week
        for (let i = 0; i < 7; i++) {
            const date = new Date(weekStart);
            date.setDate(date.getDate() + i);

            const dateStr = date.toISOString().split('T')[0];
            const dayData = this.calendarData[dateStr] || { status: 'unavailable' };

            // Check if date is within our 31-day range
            const daysDiff = Math.floor((date - this.startDate) / (1000 * 60 * 60 * 24));
            const isInRange = daysDiff >= 0 && daysDiff < 31;

            const dayCell = document.createElement('div');
            dayCell.className = `calendar-day ${isInRange ? dayData.status : 'out-of-range'}`;
            dayCell.dataset.date = dateStr;

            // Mark today
            if (dateStr === today.toISOString().split('T')[0]) {
                dayCell.classList.add('today');
            }

            // Mark selected
            if (this.selectedDate === dateStr) {
                dayCell.classList.add('selected');
            }

            // Mark out of range
            if (!isInRange) {
                dayCell.classList.add('disabled');
            }

            dayCell.innerHTML = `
                <span class="day-number">${date.getDate()}</span>
                <span class="day-indicator"></span>
            `;

            // Add tooltip for holidays/unavailable
            if (dayData.label) {
                dayCell.title = dayData.label;
            }

            container.appendChild(dayCell);
        }

        // Update week display
        this.updateWeekDisplay(weekStart);
    }

    updateWeekDisplay(weekStart) {
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekEnd.getDate() + 6);

        const monthNames = [
            'Ian', 'Feb', 'Mar', 'Apr', 'Mai', 'Iun',
            'Iul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        let displayText = `${weekStart.getDate()} - ${weekEnd.getDate()} ${monthNames[weekEnd.getMonth()]}`;
        if (weekStart.getMonth() !== weekEnd.getMonth()) {
            displayText = `${weekStart.getDate()} ${monthNames[weekStart.getMonth()]} - ${weekEnd.getDate()} ${monthNames[weekEnd.getMonth()]}`;
        }

        document.getElementById('calendar-month-year').textContent = displayText;
    }

    updateWeekNavigation() {
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');

        prevBtn.disabled = this.currentWeek === 0;
        nextBtn.disabled = this.currentWeek >= this.totalWeeks - 1;
    }

    updateWeekIndicator() {
        // Create or update week dots
        let indicator = document.querySelector('.week-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'week-indicator';
            indicator.innerHTML = `
                <span>Săptămâna ${this.currentWeek + 1} din ${this.totalWeeks}</span>
                <div class="week-dots">
                    ${Array.from({length: this.totalWeeks}, (_, i) =>
                        `<span class="week-dot ${i === this.currentWeek ? 'active' : ''}" data-week="${i}"></span>`
                    ).join('')}
                </div>
            `;
            document.querySelector('.calendar-panel').appendChild(indicator);
        } else {
            indicator.querySelector('span').textContent = `Săptămâna ${this.currentWeek + 1} din ${this.totalWeeks}`;
            indicator.querySelectorAll('.week-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === this.currentWeek);
            });
        }
    }

    goToWeek(weekIndex) {
        if (weekIndex >= 0 && weekIndex < this.totalWeeks) {
            this.currentWeek = weekIndex;
            this.render();
        }
    }

    goToPreviousWeek() {
        if (this.currentWeek > 0) {
            this.currentWeek--;
            this.render();
        }
    }

    goToNextWeek() {
        if (this.currentWeek < this.totalWeeks - 1) {
            this.currentWeek++;
            this.render();
        }
    }

    attachEventListeners() {
        // Day click handler
        document.getElementById('calendar-days').addEventListener('click', (e) => {
            const dayCell = e.target.closest('.calendar-day');
            if (!dayCell || dayCell.classList.contains('empty') || dayCell.classList.contains('disabled')) return;

            const status = this.calendarData[dayCell.dataset.date]?.status;

            // Only allow selection of available or partial days
            if (status === 'available' || status === 'partial') {
                this.selectDate(dayCell.dataset.date);
            }
        });

        // Week navigation buttons
        document.getElementById('prev-month').addEventListener('click', () => {
            this.goToPreviousWeek();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            this.goToNextWeek();
        });

        // Week dots click
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('week-dot')) {
                const weekIndex = parseInt(e.target.dataset.week);
                this.goToWeek(weekIndex);
            }
        });

        // Quick nav - first available
        document.getElementById('goto-first-available').addEventListener('click', () => {
            if (this.firstAvailableDate) {
                this.selectDate(this.firstAvailableDate);
                this.scrollToDateWeek(this.firstAvailableDate);
            }
        });

        // Suggest next day button
        document.getElementById('suggest-next-day')?.addEventListener('click', () => {
            this.goToNextAvailable();
        });

        // Sticky bar continue button
        document.getElementById('sticky-continue')?.addEventListener('click', () => {
            if (this.selectedDate && this.selectedTime) {
                // Trigger next step
                const nextBtn = document.querySelector('[data-step="3"] .next-step');
                if (nextBtn) nextBtn.click();
            }
        });
    }

    scrollToDateWeek(dateStr) {
        // Calculate which week contains this date
        const targetDate = new Date(dateStr);
        const daysDiff = Math.floor((targetDate - this.startDate) / (1000 * 60 * 60 * 24));
        const targetWeek = Math.floor(daysDiff / 7);

        if (targetWeek !== this.currentWeek && targetWeek >= 0 && targetWeek < this.totalWeeks) {
            this.currentWeek = targetWeek;
            this.render();
        }
    }

    // Show/update sticky bar
    updateStickyBar() {
        const stickyBar = document.getElementById('booking-sticky-bar');
        if (!stickyBar) return;

        // Update doctor name
        document.getElementById('sticky-doctor').textContent = this.doctorName || bookingData.doctorName || '-';

        // Update date
        if (this.selectedDate) {
            const date = new Date(this.selectedDate);
            const options = { weekday: 'short', day: 'numeric', month: 'short' };
            document.getElementById('sticky-date').textContent = date.toLocaleDateString('ro-RO', options);
        } else {
            document.getElementById('sticky-date').textContent = 'Selectați data';
        }

        // Update time
        if (this.selectedTime) {
            document.getElementById('sticky-time').textContent = this.selectedTime;
        } else {
            document.getElementById('sticky-time').textContent = 'Selectați ora';
        }

        // Update continue button state
        const continueBtn = document.getElementById('sticky-continue');
        if (continueBtn) {
            continueBtn.disabled = !(this.selectedDate && this.selectedTime);
        }

        // Show sticky bar
        stickyBar.classList.add('visible');
    }

    hideStickyBar() {
        const stickyBar = document.getElementById('booking-sticky-bar');
        if (stickyBar) {
            stickyBar.classList.remove('visible');
        }
    }

    selectDate(dateStr) {
        // Update selection state
        this.selectedDate = dateStr;
        this.selectedTime = null; // Reset time when date changes

        // Update visual selection
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.classList.remove('selected');
            if (day.dataset.date === dateStr) {
                day.classList.add('selected');
            }
        });

        // Update date display badge
        const date = new Date(dateStr);
        const options = { weekday: 'long', day: 'numeric', month: 'long' };
        document.getElementById('selected-date-badge').textContent =
            date.toLocaleDateString('ro-RO', options);

        // Update hidden form field
        document.getElementById('appointment-date').value = dateStr;

        // Update sticky bar
        this.updateStickyBar();

        // Load time slots for this date
        this.loadTimeSlots(dateStr);
    }

    async loadTimeSlots(dateStr) {
        const container = document.getElementById('time-slots-container-v2');
        const grid = document.getElementById('time-slots-grid-v2');
        const placeholder = document.getElementById('slots-placeholder');
        const loading = document.getElementById('slots-loading-v2');
        const noSlots = document.getElementById('no-slots-v2');
        const legend = document.getElementById('slots-legend-v2');

        // Show loading
        placeholder.style.display = 'none';
        loading.style.display = 'block';
        grid.style.display = 'none';
        noSlots.style.display = 'none';
        legend.style.display = 'none';

        try {
            const response = await fetch('/appointments/get-available-slots', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': getCsrfToken()
                },
                body: JSON.stringify({
                    doctor_id: this.doctorId,
                    date: dateStr,
                    service_id: this.serviceId
                })
            });

            const data = await response.json();
            loading.style.display = 'none';

            if (data.success && data.slots.length > 0) {
                this.renderTimeSlots(data.slots);
                grid.style.display = 'grid';
                legend.style.display = 'flex';
            } else {
                noSlots.style.display = 'block';
            }
        } catch (error) {
            console.error('Error loading slots:', error);
            loading.style.display = 'none';
            noSlots.style.display = 'block';
        }
    }

    renderTimeSlots(slots) {
        const grid = document.getElementById('time-slots-grid-v2');
        grid.innerHTML = '';

        slots.forEach((slot, index) => {
            const slotEl = document.createElement('div');
            slotEl.className = `time-slot-v2 ${slot.available ? '' : 'occupied'}`;
            slotEl.dataset.time = slot.time;

            const [hour, minute] = slot.time.split(':');
            const hourNum = parseInt(hour);
            const period = hourNum >= 12 ? 'PM' : 'AM';
            const displayHour = hourNum > 12 ? hourNum - 12 : (hourNum === 0 ? 12 : hourNum);

            slotEl.innerHTML = `
                <div class="slot-time">${displayHour}:${minute}</div>
                <div class="slot-period">${period}</div>
            `;

            if (slot.available) {
                slotEl.addEventListener('click', () => this.selectSlot(slotEl, slot.time));
            }

            // Animate in with stagger
            slotEl.style.opacity = '0';
            slotEl.style.transform = 'translateY(10px)';
            setTimeout(() => {
                slotEl.style.transition = 'all 0.3s ease';
                slotEl.style.opacity = '1';
                slotEl.style.transform = 'translateY(0)';
            }, index * 30);

            grid.appendChild(slotEl);
        });
    }

    selectSlot(element, time) {
        // Remove previous selection
        document.querySelectorAll('.time-slot-v2').forEach(s => s.classList.remove('selected'));

        // Add selection
        element.classList.add('selected');

        // Update internal state
        this.selectedTime = time;

        // Update form
        document.getElementById('selected-time').value = time;

        // Enable next button
        const nextBtn = document.querySelector('[data-step="3"] .next-step');
        if (nextBtn) nextBtn.disabled = false;

        // Store in booking data
        bookingData.appointmentTime = time;
        bookingData.appointmentDate = this.selectedDate;

        // Update sticky bar with time
        this.updateStickyBar();
    }

    goToNextAvailable() {
        const currentDate = this.selectedDate ? new Date(this.selectedDate) : new Date();
        currentDate.setDate(currentDate.getDate() + 1);

        for (let i = 0; i < 30; i++) {
            const dateStr = currentDate.toISOString().split('T')[0];
            const dayData = this.calendarData[dateStr];

            if (dayData && (dayData.status === 'available' || dayData.status === 'partial')) {
                this.selectDate(dateStr);
                return;
            }

            currentDate.setDate(currentDate.getDate() + 1);
        }
    }
}

// Initialize when entering Step 3
function initStep3Calendar() {
    if (bookingData.doctorId && bookingData.serviceId) {
        new AppointmentCalendar({
            doctorId: bookingData.doctorId,
            serviceId: bookingData.serviceId
        });
    }
}
```

---

## 4. Modificări Backend

### 4.1 Fișiere de Modificat

| Fișier | Modificare |
|--------|------------|
| `src/Controller/AppointmentsController.php` | Adăugare metodă `getCalendarAvailability()` |
| `src/Service/AvailabilityService.php` | Adăugare metodă `getCalendarAvailability()` |
| `templates/Appointments/index.php` | Înlocuire Step 3 HTML + CSS + JS |
| `config/app.php` | Ajustare `max_advance_days` de la 90 la 31 |

### 4.2 Controller Method

```php
// În AppointmentsController.php

/**
 * Get calendar availability for 31 days
 *
 * @return \Cake\Http\Response|null
 */
public function getCalendarAvailability(): ?Response
{
    $this->request->allowMethod(['post', 'ajax']);

    $doctorId = (int)$this->request->getData('doctor_id');
    $serviceId = (int)$this->request->getData('service_id');
    $startDate = $this->request->getData('start_date', date('Y-m-d'));
    $days = (int)$this->request->getData('days', 31);

    // Validate inputs
    if (!$doctorId || !$serviceId) {
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'success' => false,
                'message' => 'Doctor și serviciu obligatorii'
            ]));
    }

    // Limit days to 31 maximum
    $days = min($days, 31);

    $availabilityService = new AvailabilityService();
    $calendar = $availabilityService->getCalendarAvailability(
        $doctorId,
        $serviceId,
        $startDate,
        $days
    );

    // Find first available date
    $firstAvailable = null;
    foreach ($calendar as $date => $data) {
        if ($data['status'] === 'available' || $data['status'] === 'partial') {
            $firstAvailable = $date;
            break;
        }
    }

    return $this->response->withType('application/json')
        ->withStringBody(json_encode([
            'success' => true,
            'calendar' => $calendar,
            'first_available_date' => $firstAvailable
        ]));
}
```

### 4.3 AvailabilityService Method

```php
// În AvailabilityService.php

/**
 * Get calendar availability data for a range of days
 *
 * @param int $doctorId
 * @param int $serviceId
 * @param string $startDate
 * @param int $days
 * @return array
 */
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

    $holidaysTable = TableRegistry::getTableLocator()->get('HospitalHolidays');
    $unavailabilitiesTable = TableRegistry::getTableLocator()->get('StaffUnavailabilities');

    for ($i = 0; $i < $days; $i++) {
        $dateStr = $currentDate->format('Y-m-d');
        $dayOfWeek = (int)$currentDate->format('N');

        // 1. Check if date is in past
        if ($currentDate < $today) {
            $calendar[$dateStr] = [
                'status' => 'past',
                'slots_count' => 0,
                'available_count' => 0,
                'label' => null
            ];
            $currentDate->modify('+1 day');
            continue;
        }

        // 2. Check weekend
        if (in_array($dayOfWeek, [6, 7]) && !$this->allowWeekendAppointments()) {
            $calendar[$dateStr] = [
                'status' => 'weekend',
                'slots_count' => 0,
                'available_count' => 0,
                'label' => null
            ];
            $currentDate->modify('+1 day');
            continue;
        }

        // 3. Check hospital holidays
        $holiday = $holidaysTable->find()
            ->where(['date' => $dateStr])
            ->first();

        if ($holiday) {
            $calendar[$dateStr] = [
                'status' => 'holiday',
                'slots_count' => 0,
                'available_count' => 0,
                'label' => $holiday->name
            ];
            $currentDate->modify('+1 day');
            continue;
        }

        // 4. Check doctor unavailability
        $unavailability = $unavailabilitiesTable->find()
            ->where([
                'staff_id' => $doctorId,
                'date_from <=' => $dateStr,
                'date_to >=' => $dateStr
            ])
            ->first();

        if ($unavailability) {
            $calendar[$dateStr] = [
                'status' => 'unavailable',
                'slots_count' => 0,
                'available_count' => 0,
                'label' => $unavailability->reason ?? 'Indisponibil'
            ];
            $currentDate->modify('+1 day');
            continue;
        }

        // 5. Get available slots for this day
        $slots = $this->getAvailableSlots($doctorId, $dateStr, $serviceId);
        $totalCount = count($slots);
        $availableCount = count(array_filter($slots, fn($s) => $s['available']));

        // Determine status based on availability
        if ($totalCount === 0) {
            $status = 'unavailable';
        } elseif ($availableCount === 0) {
            $status = 'full';
        } elseif ($availableCount <= $totalCount * 0.3) {
            $status = 'partial';
        } else {
            $status = 'available';
        }

        $calendar[$dateStr] = [
            'status' => $status,
            'slots_count' => $totalCount,
            'available_count' => $availableCount,
            'label' => null
        ];

        $currentDate->modify('+1 day');
    }

    return $calendar;
}
```

---

## 5. Plan de Implementare

### Pașii de Implementare

1. **Backend - Endpoint Calendar**
   - Adaugă rută nouă în `config/routes.php`
   - Implementează `getCalendarAvailability()` în controller
   - Implementează logica în `AvailabilityService`
   - Testează endpoint-ul cu Postman/curl

2. **Frontend - HTML Structure**
   - Înlocuiește Step 3 în `templates/Appointments/index.php`
   - Adaugă noul HTML pentru calendar și time slots

3. **Frontend - CSS Styles**
   - Adaugă stilurile CSS pentru calendar
   - Adaugă stilurile pentru time slots panel
   - Testează responsive design

4. **Frontend - JavaScript Logic**
   - Implementează clasa `AppointmentCalendar`
   - Integrează cu fluxul existent de booking
   - Testează interacțiunile

5. **Testing End-to-End**
   - Testează fluxul complet de booking
   - Testează scenarii: weekend, sărbători, medic indisponibil
   - Testează pe mobile

### Verificare Finală

**Calendar săptămânal:**
- [ ] Navigarea pe săptămâni (◀ ▶) funcționează corect
- [ ] Indicator săptămână (dots) se actualizează și permite click
- [ ] Zilele în afara celor 31 zile sunt dezactivate vizual
- [ ] Ziua curentă este marcată cu "Azi"
- [ ] Zilele de weekend sunt marcate ca indisponibile
- [ ] Sărbătorile spitalului sunt afișate cu numele lor (tooltip)
- [ ] Indisponibilitățile medicului blochează zilele respective
- [ ] Selecția datei evidențiază vizual ziua selectată

**Sloturi de timp:**
- [ ] Toate sloturile se afișează (disponibile + ocupate)
- [ ] Sloturile ocupate sunt tăiate vizual și nu pot fi selectate
- [ ] Selecția slot-ului activează butonul Next
- [ ] Animații fade-in pentru sloturi
- [ ] Butonul "Prima zi disponibilă" funcționează și navighează la săptămâna corectă

**Sticky Summary Bar:**
- [ ] Bara apare când intri în Step 3
- [ ] Se actualizează la selecția datei
- [ ] Se actualizează la selecția orei
- [ ] Butonul "Continuă" este dezactivat până la selecție completă
- [ ] Click pe "Continuă" avansează la Step 4
- [ ] Bara dispare când ieși din Step 3

**Responsive:**
- [ ] Layout funcționează pe mobile (sub 768px)
- [ ] Sticky bar se adaptează pe mobile
- [ ] Calendar și sloturi se redimensionează corect

**Fluxul complet:**
- [ ] Step 1 → Step 2 → Step 3 → Step 4 → Step 5 funcționează
- [ ] Datele se transmit corect între pași
- [ ] Programarea se salvează în baza de date

---

## 6. Considerații Adiționale

### Performance
- Calendar data se încarcă o singură dată la intrarea în Step 3
- Time slots se încarcă doar la selecția unei zile
- Animațiile sunt CSS-based pentru performance

### Accessibility
- Contrast adecvat pentru toate stările
- Tooltips pentru sărbători și indisponibilități
- Keyboard navigation pentru calendar
- Screen reader labels

### UX Improvements
- Auto-select prima zi disponibilă la intrare
- Highlight clar pentru ziua curentă
- Feedback vizual instant la selecție
- Tranziții smooth între stări
