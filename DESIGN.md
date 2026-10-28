---
name: Informatika UHO Academic Space
colors:
  surface: '#fbf8ff'
  surface-dim: '#d5d8f6'
  surface-bright: '#fbf8ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f4f2ff'
  surface-container: '#ececff'
  surface-container-high: '#e5e6ff'
  surface-container-highest: '#dee1ff'
  on-surface: '#161a30'
  on-surface-variant: '#464555'
  inverse-surface: '#2b2f46'
  inverse-on-surface: '#f0efff'
  outline: '#777586'
  outline-variant: '#c8c4d7'
  surface-tint: '#5246dc'
  primary: '#4233cc'
  on-primary: '#ffffff'
  primary-container: '#5b50e5'
  on-primary-container: '#e8e4ff'
  inverse-primary: '#c4c0ff'
  secondary: '#4648d4'
  on-secondary: '#ffffff'
  secondary-container: '#6063ee'
  on-secondary-container: '#fffbff'
  tertiary: '#00557a'
  on-tertiary: '#ffffff'
  tertiary-container: '#006e9e'
  on-tertiary-container: '#d2eaff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e3dfff'
  primary-fixed-dim: '#c4c0ff'
  on-primary-fixed: '#120068'
  on-primary-fixed-variant: '#3926c4'
  secondary-fixed: '#e1e0ff'
  secondary-fixed-dim: '#c0c1ff'
  on-secondary-fixed: '#07006c'
  on-secondary-fixed-variant: '#2f2ebe'
  tertiary-fixed: '#c9e6ff'
  tertiary-fixed-dim: '#89ceff'
  on-tertiary-fixed: '#001e2f'
  on-tertiary-fixed-variant: '#004c6e'
  background: '#fbf8ff'
  on-background: '#161a30'
  surface-variant: '#dee1ff'
  canvas-bg: '#F4F5F9'
  surface-card: '#FFFFFF'
  surface-card-subtle: '#F9FAFC'
  status-active-bg: '#EEF2FF'
  status-active-text: '#4F46E5'
  status-done-bg: '#F1F3F7'
  status-done-text: '#64748B'
  status-upcoming-bg: '#ECFDF5'
  status-upcoming-text: '#059669'
  action-danger: '#EF4444'
  action-danger-bg: '#FEF2F2'
  border-subtle: '#E5E7EB'
  text-muted: '#8A94A6'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 30px
    fontWeight: '700'
    lineHeight: 38px
    letterSpacing: -0.02em
  headline-xl-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 22px
    fontWeight: '700'
    lineHeight: 30px
    letterSpacing: -0.015em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 26px
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 15px
    fontWeight: '600'
    lineHeight: 22px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 24px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 18px
  label-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 13px
    fontWeight: '600'
    lineHeight: 18px
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 11px
    fontWeight: '600'
    lineHeight: 14px
    letterSpacing: 0.02em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1.25rem
  gutter-mobile: 0.75rem
  margin: 1.75rem
  margin-mobile: 1rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
---

## Brand & Style

This design system establishes a focused, high-clarity academic command center for the Department of Informatics (Teknik Informatika) at Universitas Halu Oleo. The system balances administrative authority with contemporary software aesthetics, transforming scheduling from static paperwork into a live, time-aware spatial feed.

Targeting university students, lecturers, academic coordinators, and system administrators, the aesthetic aligns with Modern SaaS Dashboard design. It leverages an expansive ultra-light neutral canvas (`#F4F5F9`), crisp white floating cards, subtle pastel status containers, and energetic indigo/violet brand anchors (`#5B50E5` and `#6366F1`). The emotional resonance is calm, orderly, and immediate: users can survey complex class timetables, ongoing room allocations, and administrative controls without cognitive fatigue.

## Colors

The color palette drives functional clarity and time-based status hierarchy across academic records:

- **Primary (`#5B50E5`) & Secondary (`#6366F1`):** Form the chromatic core used for active navigation states, primary triggers ("Tambah Jadwal"), hero gradient banners, and active temporal highlights.
- **Tertiary (`#0EA5E9`):** Represents utility actions including bulk imports ("Unggah Excel"), download options, and laboratory schedule tags.
- **Neutral (`#1E2238`):** Deep charcoal ink used for high-contrast headline readability without the harshness of pure black.
- **Canvas (`#F4F5F9`) & Surface Cards (`#FFFFFF`):** A soft, cool background provides natural contrast for pure white elevated panels and segmented cards.
- **Semantic Badges:**
  - `Sedang Berlangsung` (In Progress): Tinted indigo badge (`#EEF2FF` background, `#4F46E5` text) with a live pulse indicator.
  - `Selesai` (Completed): Subdued slate badge (`#F1F3F7` background, `#64748B` text) signifying concluded sessions.
  - `Belum Mulai` (Upcoming): Crisp emerald tint (`#ECFDF5` background, `#059669` text) signaling pending sessions for the day.
  - `Hapus` (Destructive): Coral crimson (`#EF4444`) with soft red tinted container backgrounds (`#FEF2F2`).

## Typography

Typography is set exclusively in **Plus Jakarta Sans**, providing modern geometry, friendly rounded letterforms, and high legibility across dense data matrices.

- **Scale Distribution:** Page titles utilize `headline-xl` paired with compact trackings to anchor the top of workspace views. Card titles (such as Course Names and Room IDs) rely on `headline-md` and `headline-sm`.
- **Tabular & Meta Data:** Class details (SKS, semester, angkatan, NIDN) utilize `body-sm` and `label-md` to maintain legibility when scanning schedules.
- **Caps & Pill Badges:** Micro-labels and status indicators leverage `label-sm` with a mild tracking boost (`0.02em`) for clarity when rendered in small badges.

## Layout & Spacing

The system implements a structured 12-column fluid grid coupled with a fixed-width left navigation rail:

- **Desktop (>= 1280px):**
  - Left navigation sidebar: fixed `260px` width.
  - Main operational workspace: dynamic fluid grid with `1.25rem` (20px) gutters and `1.75rem` (28px) canvas margins.
  - Right auxiliary panel (Stats, Lecturer Profile, Quick Calendar): optional `340px` column or integrated directly into standard 4-column module stacks.
- **Tablet (768px - 1279px):**
  - Left navigation collapses to an icon-only strip (`80px`).
  - Gutter adjusts to `1rem` (16px), margins scale to `1.25rem` (20px). Main grid operates as an 8-column layout.
- **Mobile (< 768px):**
  - Sidebar stows into an off-canvas drawer accessed via a floating or header app-bar.
  - Layout becomes a single fluid column with `0.75rem` gutters and `1rem` margins. Schedule cards expand to 100% width.

## Elevation & Depth

Visual depth is achieved through ultra-soft ambient shadowing and tinted tonal layering rather than hard perimeter borders:

- **Base Surfaces:** The page rests on `canvas-bg` (`#F4F5F9`). All dashboard containers, schedule cards, and stats widgets sit on pure white (`#FFFFFF`).
- **Ambient Shadow Levels:**
  - `Level 0 (Flat)`: Used for internal table rows, form inputs, and passive pill buttons. Supported by a subtle outline border (`1px solid #E5E7EB`).
  - `Level 1 (Card Rest)`: `0px 4px 20px -2px rgba(91, 80, 229, 0.04), 0px 2px 6px -1px rgba(27, 33, 61, 0.03)`. A light, indigo-tinted diffused shadow for standard schedule widgets and stat panels.
  - `Level 2 (Hover / Active Session)`: `0px 10px 28px -4px rgba(91, 80, 229, 0.12), 0px 4px 10px -2px rgba(27, 33, 61, 0.04)`. Elevates the card for lectures currently in progress (`Sedang Berlangsung`) or cards hovered by the cursor.
  - `Level 3 (Modal / Flyout)`: `0px 20px 40px -8px rgba(30, 34, 56, 0.16)`. Applied to schedule creation drawers, time pickers, and filter overlays.

## Shapes

The design uses balanced, rounded geometry (`roundedness: 2`) to echo the soft card design language of the dashboard:

- **Standard Components (`0.5rem` / 8px):** Form inputs, action buttons, table cell badges, and dropdown menus.
- **Cards & Data Containers (`1rem` / 16px):** Schedule cards, metric summary blocks, and table envelopes.
- **Hero Containers (`1.5rem` / 24px):** Large academic announcement banners and graphic dashboard cards.
- **Pills (`9999px`):** Status indicator tags (`Sedang Berlangsung`, `Selesai`, `Belum Mulai`), search input fields, and category filters.

## Components

### Buttons
- **Primary Action ("Tambah Jadwal / Simpan"):** Solid `#5B50E5` fill with pure white label, `0.5rem` border radius, `0.625rem 1.25rem` padding. On hover, shifts to `#4F46E5` with a subtle elevation shift.
- **Secondary Action ("Unggah Excel"):** Soft secondary tint with `#EEF2FF` background and `#4F46E5` text, or tertiary blue outline.
- **Row Utility ("Ubah"):** Neutral low-contrast background (`#F1F3F7`) with `#475569` text, `0.375rem` padding.
- **Destructive ("Hapus"):** Soft crimson surface (`#FEF2F2`) with `#EF4444` text; deepens to solid crimson on confirmation dialogs.

### Schedule Cards (`Kartu Jadwal`)
- White surface (`#FFFFFF`) featuring a `1rem` radius and Level 1 elevation.
- **Active State (`Sedang Berlangsung`):** Accented with an active border highlight (or left vertical 4px bar in `#5B50E5`), Level 2 elevation, and a live pill badge containing an indigo pulsing dot.
- **Completed State (`Selesai`):** Low saturation text (`#8A94A6`), background shifts to subtle `#F9FAFC`, paired with a muted gray pill.
- **Upcoming State (`Belum Mulai`):** Clear neutral typography, emerald badge, displaying remaining time until start.

### Pill Badges & Chips
- Constructed with full roundedness (`rounded-full`), `space-xs` vertical padding, and `space-sm` horizontal padding.
- Used for semester levels (e.g., `Sem. IV`), class groups (`Kelas A`, `Kelas B`), credit counters (`3 SKS`), and lecture states.

### Data Tables
- Encased in a rounded white container with header rows shaded in `#F9FAFC`.
- Cells are padded with `0.875rem 1rem`, maintaining clear horizontal divider borders (`#E5E7EB`). 
- Features sticky left columns for Course Codes and inline action triggers on the far right.

### Search & Filter Bar
- Top-level search input utilizes full pill styling (`rounded-full`), interior search icon, and a neutral placeholder (`#8A94A6`) on a white background with Level 1 elevation.
- Dropdown select pickers for Day (`Senin` - `Jumat`), Room (`Lab Software`, `Ruang 01`), and Batch (`Angkatan`) use pill borders with smooth hover transitions.

### Sidebar Navigation
- Vertical navigation rail with a brand lockup at top (Teknik Informatika UHO emblem + title).
- Links feature `0.75rem` rounded hover/active tracks; the active navigation item displays a vibrant `#5B50E5` background with white text and an ambient indicator glow.