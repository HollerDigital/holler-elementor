# Holler Elementor Extension

Custom Elementor extension developed by Holler Digital.

## Description

The Holler Elementor Extension adds custom widgets and functionality to the Elementor page builder, enhancing your WordPress site with additional features tailored specifically for Holler Digital clients.

## Features

- Custom Team Member widget with bio modal
- Proper integration with Elementor editor and frontend
- Dynamic tag support for team member fields
- Memory-safe rendering for resource-intensive content

## Version History

### 2.3.3 (July 22, 2025)
- **Team Widget Social Icons Enhancement**
  - Added LinkedIn and Instagram social media icons to team member widget
  - Implemented proper HTML structure to avoid nested anchor tags
  - Added comprehensive style controls for social icons (color, hover color, size, spacing, alignment)
  - Social icons positioned outside main team member link to maintain full functionality
  - Icons default to center alignment with customizable left/center/right options
  - Fixed spacing controls to add both top margin and horizontal spacing
  - Social links open in new tabs while preserving bio modal and external URL functionality

### 2.3.2 (January 20, 2025)
- **Enhanced Typography System**
  - Added Pre-Header, Text Small, and Text Big heading sizes to responsive heading customizer
  - Added line height and font weight controls for each heading size
  - Complete typography control system with size, line height, and font weight for all heading sizes
  - Responsive defaults that scale appropriately across desktop, tablet, and mobile devices
  - Updated Elementor heading control to apply all three typography properties (size, line height, font weight)

- **Improved Spacing Customizer**
  - Fixed spacing customizer unit saving issue - now properly saves both numeric values and units
  - Added automatic migration system for backward compatibility with legacy spacing values
  - Split value/unit system allows independent control of numbers and units (px, rem, em, %, vh, vw)
  - Enhanced range controls with unit selection dropdowns
  - Improved CSS output to include proper units in custom properties

- **Backward Compatibility**
  - Automatic migration converts old "20px" style values to split numeric (20) + unit (px) system
  - Version-tracked migration prevents duplicate conversions
  - Debug logging for troubleshooting migration process
  - Seamless upgrade experience for existing users

### 2.2.19 (June 19, 2025)
- **Bug Fixes & Improvements**
  - Fixed Elementor control conflict (duplicate declaration issue with container spacing controls)
  - Removed redundant CSS section from plugin settings (now using customizer)
  - Disabled debug logging for improved performance

### 2.2.18 (June 18, 2025)
- **Maintenance Update**
  - Version increment for deployment tracking
  - Additional internal code refinements
  - Preparation for upcoming feature development

### 2.2.17 (June 18, 2025)
- **Plugin Update System Improvements**
  - Added plugin icon support for update screens
  - Enhanced plugin updater class with icon metadata
  - Prepared icon structure in assets directory

### 2.2.16 (June 18, 2025)
- **Enhanced Responsive Customizer Integration**
  - Added responsive spacing customizer with desktop, tablet, and mobile controls
  - Added responsive heading size customizer with device-specific settings
  - Organized customizer settings under a unified "Holler Elementor" panel
  - Integrated with theme breakpoint settings for consistent responsive behavior
  - Added directional padding CSS variables for complex layouts
  - Improved heading size controls with better variable naming
  - Fixed spacing and heading control issues in Elementor widgets

### 2.2.15 (May 7, 2025)
- **Code Refactoring and Security Improvements**
  - Comprehensive code refactoring for improved maintainability
  - Enhanced data sanitization and validation throughout the plugin
  - Security improvements for form inputs and data handling
  - Performance optimizations

### 2.2.14 (May 6, 2025)
- **Improved Elementor Compatibility**
  - Fixed compatibility issues with latest Elementor version
  - Improved error handling and debugging for custom controls
  - Enhanced container spacing control with proper default values
  - Fixed option name inconsistency in settings

### 2.2.13 (May 6, 2025)
- **Enhanced Plugin Settings**
  - Added settings page under WordPress Settings menu
  - Added options to enable/disable individual Elementor extensions
  - Added custom CSS section for widget styling

- **Modular Control Architecture**
  - Separated custom Elementor controls into individual classes
  - Implemented conditional loading based on settings
  - Improved code organization and maintainability

### 2.2.12 (May 6, 2025)
- **Fixed Style Loading Issues**
  - Implemented proper style and script registration
  - Added hooks for both frontend and editor contexts

- **Improved Elementor Compatibility**
  - Updated the widget to follow Elementor's best practices
  - Added `get_style_depends()` and `get_script_depends()` methods
  - Implemented `content_template()` for live preview in the editor

- **Added Dynamic Tags Support**
  - Added dynamic tags to team member image
  - Added dynamic tags to team member name
  - Added dynamic tags to team member title

- **Code Organization**
  - Removed duplicate style and script registrations
  - Improved code documentation
  - Made the plugin more maintainable

## Installation

1. Upload the plugin files to the `/wp-content/plugins/holler-elementor` directory
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the new widgets in your Elementor editor

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0.0 or higher
- PHP 8.1 or higher

## Support

For support, please contact [Holler Digital](https://hollerdigital.com/).

## License

This plugin is licensed under the GPL v2 or later.
