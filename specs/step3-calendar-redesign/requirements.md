# Requirements: Step 3 Calendar Redesign

## Overview

Redesign Step 3 of the appointment booking flow from a basic HTML5 date input to an interactive 31-day calendar with week navigation, visual availability indicators, and a sticky summary bar.

## Problem Statement

The current Step 3 uses a simple `<input type="date">` which:
- Doesn't show availability across multiple days at a glance
- Requires users to pick dates blindly without knowing which have slots
- Provides no visual feedback about partial availability or holidays
- Lacks a summary of selections during the booking process

## Solution

A two-panel calendar interface:
1. **Calendar Panel**: 7-day week view with 5-week navigation showing day-level availability
2. **Time Slots Panel**: Grid of available/occupied time slots for selected date
3. **Sticky Summary Bar**: Fixed bar showing Doctor | Date | Time | Continue button

## Acceptance Criteria

### Calendar Panel
- [ ] Displays 7 days per week with left/right navigation
- [ ] Shows 5 weeks total (approximately 31 days from today)
- [ ] Each day cell shows availability status with color coding:
  - Green: Available (many slots)
  - Yellow: Partial (< 30% slots available)
  - Gray: Full (no slots)
  - Red: Holiday (with name on hover)
  - Light gray: Weekend (if weekends disabled)
- [ ] Today is marked with "Azi" badge
- [ ] Past days are visually disabled
- [ ] Clicking available/partial day selects it and loads time slots
- [ ] "Prima zi disponibilă" (First available) button jumps to first available date
- [ ] Week indicator dots show current week position

### Time Slots Panel
- [ ] Shows loading state while fetching slots
- [ ] Shows placeholder message when no date selected
- [ ] Shows "no slots" message with "Next available day" button when empty
- [ ] Displays all slots (available and occupied) in a grid
- [ ] Occupied slots show strikethrough and are not clickable
- [ ] Selected slot has checkmark and highlight
- [ ] Legend explains slot states

### Sticky Summary Bar
- [ ] Fixed at bottom of viewport when on Step 3
- [ ] Shows: Doctor name | Selected date | Selected time
- [ ] "Continuă" button disabled until date AND time selected
- [ ] Button click advances to Step 4
- [ ] Hidden when not on Step 3

### Backend API
- [ ] New endpoint: `POST /appointments/get-calendar-availability`
- [ ] Returns 31 days of availability data in single request
- [ ] Each day includes: status, slots_count, available_count, label (for holidays)
- [ ] Returns `first_available_date` for quick navigation

### Mobile Responsive
- [ ] Single column layout on screens < 900px
- [ ] Touch-friendly day and slot selection
- [ ] Sticky bar adapts to mobile layout

## Dependencies

- Existing `AvailabilityService.php` methods for slot calculation
- Existing `getAvailableSlots` endpoint for time slot loading
- Existing `bookingData` JavaScript object for state management
- Hospital holidays table (`hospital_holidays`)
- Staff unavailabilities table (`staff_unavailabilities`)

## Related Features

- Step 1: Specialty selection (provides specialty context)
- Step 2: Doctor & service selection (provides `doctorId`, `serviceId`)
- Step 4: Patient details (receives `appointmentDate`, `appointmentTime`)
