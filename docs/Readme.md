# Yellow Cloaker Documentation

This documentation provides instructions for using and configuring the Yellow
Cloaker system.

## Access to Configuration

All settings are now managed through the web interface available at:

```
/admin?password=12345
```

**Important Note**: Please change the default password immediately after
installation for security purposes.

## System Requirements

- PHP version 7.2 or higher
- HTTPS certificates for all your domains
- No WordPress installation required

## Traffic Distribution System (TDS)

The system offers a complete solution for managing and distributing web traffic
with the following functionalities:

### User Flow Management

- **White/Black Mode**: Dual redirection system that allows directing visitors
  to different destinations based on configurable criteria.
- **303 Redirections**: HTTP redirection configuration to specific pages.
- **Domain Filtering**: Ability to filter access based on specific domains.

### Security and Filters

- **JavaScript Verifications**: Ability to perform security checks such as
  audiocontext and timezone.
- **TDS Filters**: Configuration of filters by countries, operating systems,
  IPs, user agents, ISPs, and VPN/Tor detection.
- **URL Parameters**: Support for filtering based on specific parameters in the
  URL.

### Tracking and Analysis

- **SubID System**: Support for multiple sub-tracking identifiers with parameter
  rewriting capability.
- **Statistics**: Password-protected panel for viewing performance with support
  for multiple subnames.
- **Postbacks**: Event notification system (Lead, Purchase, Reject, Trash)
  including support for S2S webhooks.

### Marketing Platform Integration

- **Tracking Pixels**: Support for Facebook Pixel, TikTok Pixel, Yandex Metrika,
  and Google Tag Manager.
- **Customizable Events**: Configuration of conversion and content view events.

### UX/UI Features

- **Interactive Scripts**: Functionalities such as custom back button, text copy
  deactivation, phone masks.
- **Optimization**: Lazy loading of images for better performance.
- **Interactive Widgets**: Comebacker, callbacker, and "added to cart"
  notification system.

### Content Management

- **Thank You Page Customization**: Ability to configure thank you pages with
  upsell options.
- **Conversion Script Integration**: Ability to integrate custom scripts for
  conversion tracking.

## Important Implementation Notes

- Always use relative links within the project
- The project has no relationship with WordPress
- There should be no redirects within the system
- Do not include index.php inside offer or white page folders as they are
  managed by the end user
- The YellowCloaker-master folder is for research purposes only

## Testing Your Implementation

You can test the implementation using curl:

```
curl -s "http://localhost:8000"
```

Remember to test all navigation paths created between pages.
