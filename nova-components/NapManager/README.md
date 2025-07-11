# NAP Manager - Laravel Nova Tool

This Laravel Nova tool allows you to manage Network Access Points (NAPs) with location tracking and distribution visualization.

## Features

- **Map View**: Visualize NAP boxes on Google Maps based on their geographic coordinates
- **Flow View**: Organize and visualize NAP boxes in a hierarchical distribution flow
- **Status Tracking**: Monitor the status and occupancy of NAP boxes
- **Interactive Interface**: Drag and drop NAP boxes in the flow view to organize them

## Installation

You can install the package via composer:

```bash
composer require ispgo/nap-manager
```

## Configuration

1. Add the Google Maps API key to your `.env` file:

```
MIX_GOOGLE_MAPS_API_KEY=YOUR_GOOGLE_MAPS_API_KEY
```

2. Register the tool in your `NovaServiceProvider.php`:

```php
// in app/Providers/NovaServiceProvider.php

public function tools()
{
    return [
        // ... other tools
        new \Ispgo\NapManager\NapManager(),
    ];
}
```

3. Run migrations to create the necessary database tables:

```bash
php artisan migrate
```

## Usage

### NAP Boxes

NAP boxes represent physical Network Access Points with the following attributes:

- **Name**: Descriptive name of the NAP box
- **Code**: Unique identifier code
- **Address**: Physical address where the NAP box is located
- **Latitude/Longitude**: Geographic coordinates for map visualization
- **Status**: Current operational status (active, inactive, maintenance, damaged)
- **Capacity**: Total number of ports available
- **Technology Type**: Type of technology used (fiber, coaxial, ftth, mixed)
- **Distribution Order**: Order in the distribution hierarchy

### Map View

The Map View displays NAP boxes on Google Maps based on their geographic coordinates. Features include:

- Color-coded markers based on status and occupancy
- Info windows with detailed information when clicking on markers
- Legend explaining the marker colors
- Refresh button to reload the data

### Flow View

The Flow View displays NAP boxes in a hierarchical distribution flow. Features include:

- Drag and drop interface to organize NAP boxes
- Automatic saving of position changes
- Connection lines showing parent-child relationships
- Color-coded nodes based on status
- Occupancy visualization with progress bars
- Minimap for navigation in large flows

## Development

### Prerequisites

- Node.js and npm
- Laravel Nova
- PHP 8.1 or higher

### Setup for Development

1. Clone the repository
2. Install dependencies:

```bash
cd nova-components/NapManager
composer install
npm install
```

3. Build the assets:

```bash
npm run dev
```

For production:

```bash
npm run prod
```

### Tailwind CSS Integration

This package uses Tailwind CSS for styling. The configuration is set up to:

- Include all Tailwind CSS classes, not just the ones used by Laravel Nova
- Process CSS with PostCSS and Autoprefixer
- Scan all Vue components and PHP files for Tailwind classes
- Scope Tailwind CSS to only affect the NapManager component

The Tailwind CSS configuration has been set up to prevent it from affecting other parts of your application:

- All Tailwind CSS classes are prefixed with `nap-` to avoid conflicts
- The `preflight` feature is disabled to prevent global style resets
- Tailwind CSS directives are wrapped in a `.nap-manager-wrapper` class to scope them
- All components use the prefixed classes to maintain styling

To customize Tailwind CSS:

1. Edit the `tailwind.config.js` file to add custom themes, plugins, or other configurations
2. Add custom styles in `resources/css/tool.css` below the Tailwind directives
3. Remember to use the `nap-` prefix for any new Tailwind CSS classes
4. Rebuild the assets with `npm run dev` or `npm run prod`

### Vue Components

This tool uses Vue.js for the frontend. The main components are:

- `resources/js/pages/Tool.vue`: Main container with tabs
- `resources/js/components/NapMapComponent.vue`: Google Maps integration
- `resources/js/components/NapFlowComponent.vue`: Vue Flow integration

## License

The MIT License (MIT).
