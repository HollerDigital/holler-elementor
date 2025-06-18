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
