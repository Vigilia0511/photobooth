# Photobooth Web App

A web-based photobooth application that allows users to capture photos using their device's camera, edit them with frames and stickers, and email the resulting photo strips.

## Features

- **Camera Capture**: Access device camera for photo capture
- **Multiple Photo Modes**: Single capture or continuous capture mode
- **Photo Editing**: Add frames and stickers to photos
- **Photo Strips**: Create 4-photo or 8-photo strips
- **Email Integration**: Send photo strips via email using PHPMailer
- **Responsive Design**: Works on desktop and mobile devices
- **File Upload**: Alternative to camera capture via file upload

## Requirements

- PHP 7.0 or higher
- Web server (e.g., Apache, Nginx)
- HTTPS for camera access (recommended for production)
- Gmail account for email functionality (or configure your own SMTP)

## Installation

1. **Clone or Download** the project files to your web server directory.

2. **Install PHPMailer**:
   - The PHPMailer library is already included in the `PHPMailer-7.0.1/` directory.
   - If you prefer, you can install it via Composer: `composer require phpmailer/phpmailer`

3. **Configure Email Settings**:
   - For detailed Gmail setup instructions, see [GMAIL_SETUP.md](GMAIL_SETUP.md)
   - Copy `.env.example` to `.env` (if it exists) or create a `.env` file
   - Update the email credentials:
     ```
     EMAIL_USERNAME=your-email@gmail.com
     EMAIL_PASSWORD=your-app-password
     ```
   - For Gmail, use an App Password instead of your regular password.

4. **Access the Application**:
   - Open `index.php` in your web browser.

## Usage

### Taking Photos

1. **Start Camera**: Click "Start Camera" to access your device's camera.
2. **Capture Mode**:
   - **Single Capture**: Click "Capture" to take individual photos.
   - **Continuous Mode**: Click "Continuous" for automatic photo capture every 3 seconds.
3. **Timer**: Select a countdown timer before capture starts.

### Editing Photos

1. After capturing photos, you'll be redirected to the edit page (`edit.php`).
2. **Layout Options**: Choose between 4-photo or 8-photo strips.
3. **Frames**: Select from available frames in the `frames/` directory.
4. **Stickers**: Add stickers to your photos (if implemented).
5. **Hashtags**: Add custom hashtags to your photo strip.

### Emailing Photos

1. Click "Email" to send the photo strip.
2. The strip will be emailed to the configured email address.

## File Structure

```
photobooth/
├── index.php          # Main photobooth interface
├── edit.php           # Photo editing page
├── save_image.php     # Handles photo uploads
├── send_email.php     # Email functionality
├── list_images.php    # Lists uploaded images
├── script.js          # Client-side JavaScript
├── style.css          # CSS styling
├── .env               # Environment variables (email config)
├── .gitignore         # Git ignore file
├── frames/            # Photo frame images
├── uploads/           # Uploaded photos (created automatically)
└── PHPMailer-7.0.1/   # PHPMailer library
    ├── src/
    └── language/
```

## Configuration

### Email Configuration

Edit the `.env` file to configure email settings:

```
EMAIL_USERNAME=your-email@gmail.com
EMAIL_PASSWORD=your-app-password
```

### Camera Settings

The application automatically detects available cameras and allows switching between front and back cameras on mobile devices.

### Frame Customization

Add your own frame images to the `frames/` directory. Frames should be PNG files with transparent backgrounds for best results.

## Security Notes

- The `.env` file contains sensitive information and is ignored by Git.
- Ensure your web server is configured securely.
- Use HTTPS in production to enable camera access.
- Regularly update PHPMailer to the latest version for security patches.

## Troubleshooting

### Camera Not Working

- Ensure you're accessing the site via HTTPS (required for camera access).
- Check browser permissions for camera access.
- Try refreshing the page or restarting your browser.

### Email Not Sending

- Verify your email credentials in `.env`.
- For Gmail, ensure you're using an App Password.
- Check your spam folder.
- Review server error logs for PHPMailer errors.

## Contributing

Feel free to submit issues, feature requests, or pull requests to improve the photobooth application.

## License

This project is open source. Please check individual component licenses (especially PHPMailer).

## Credits

- Built with PHP, JavaScript, HTML5, and CSS3
- Email functionality powered by [PHPMailer](https://github.com/PHPMailer/PHPMailer)
- Camera access using WebRTC/MediaDevices API