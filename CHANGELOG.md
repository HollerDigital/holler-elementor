# Changelog

All notable changes to the Holler Elementor Extension will be documented in this file.

## [2.3.55] - 2025-12-18

### Added
- Easy Digital Downloads Software Licensing SDK integration for plugin updates
- License key management in Settings → Holler Elementor page
- License activation/deactivation functionality with status display
- License expiration info display

### Removed
- Old GitHub-based plugin-update-checker library
- class-plugin-updater.php (replaced by EDD SL SDK)

## [2.3.54] - 2025-09-17

### Changed
- Removed jConveyorTicker CDN CSS/JS registrations and enqueues in `inc/class-plugin-loader.php`.
- Updated `inc/widgets/holler-conveyor.php` to drop `holler-conveyor-lib` from `get_style_depends()` and `get_script_depends()`.
- Removed dead jConveyorTicker initialization block from `assets/js/holler-elementor-app.js`.

### Why
- Conveyor widget now uses a CSS-based marquee implementation (`inc/layouts/holler-conveyor.php`) and no longer relies on the jQuery plugin.
- Reduces network requests, payload size, and eliminates unused code.

## [2.3.53] - 2025-08-18

### Added
- New Conveyor widget (scrolling ticker)
  - Items support text, optional link, and optional icon
  - Icon controls: size, position (left/right), color, spacing
  - Controls for animation duration, item gap, and track height
  - Pause on hover toggle respected in frontend and editor
  - Reverse direction toggle via widget setting

### Changed
- Improved live editor preview parity for Conveyor widget (direction, spacing, color, icons)

### Fixed
- Color control applies to ticker text and icons
- Spacing made robust with flex gap patterns
- Safer sanitization and escaping across Conveyor output

### 2.3.3 (July 22, 2025)
- **Team Widget Social Icons Enhancement**
  - Added LinkedIn and Instagram social media icons to team member widget
  - Implemented proper HTML structure to avoid nested anchor tags
  - Added comprehensive style controls for social icons (color, hover color, size, spacing, alignment)
  - Social icons positioned outside main team member link to maintain full functionality
  - Icons default to center alignment with customizable left/center/right options
  - Fixed spacing controls to add both top margin and horizontal spacing
  - Social links open in new tabs while preserving bio modal and external URL functionality


## [2.3.1] - 2025-06-24

### Added
- Migrated Elementor button styles from theme CSS
- Migrated Elementor button styling JavaScript from theme
- Added CSS files for Elementor button customization

### Changed
- Completed migration of all Elementor-specific code from theme
- Improved organization of Elementor styling components

## [2.3.0] - 2025-06-24

### Added
- New customizer class for Elementor enhancements (class-elementor-enhancements-customizer.php)
- Dedicated sanitization functions for customizer settings
- Support for Elementor button style customizations
- Post type template selection in customizer

### Changed
- Migrated all Elementor-related customizer settings from theme to plugin
- Enhanced SCSS structure with dedicated Elementor partials
- Updated main SCSS file to include new Elementor component imports

### Improved
- Better organization of Elementor-specific code
- Modular customizer architecture using OOP principles
- Centralized all Elementor customizations in one plugin

## [2.2.19] - Previous version
