# Gmail Setup for Photobooth Email

This guide explains how to configure Gmail to send emails from your photobooth application using PHPMailer.

## Prerequisites

- A Gmail account
- 2-Step Verification enabled on your Gmail account
- Access to your photobooth project's `.env` file

## Step 1: Enable 2-Step Verification

1. Go to your [Google Account settings](https://myaccount.google.com/)
2. Navigate to **Security** in the left sidebar
3. Under "Signing in to Google", click on **2-Step Verification**
4. Follow the prompts to enable 2-Step Verification
5. Verify your phone number and complete the setup

## Step 2: Generate an App Password

1. While still in your Google Account settings, go back to **Security**
2. Under "Signing in to Google", click on **App passwords**
   - If you don't see this option, make sure 2-Step Verification is enabled
3. You might need to sign in again
4. Select **Mail** as the app
5. Select **Other (custom name)** as the device
6. Enter "Photobooth" as the custom name
7. Click **Generate**
8. Copy the 16-character password that appears (ignore spaces)

**Important**: This app password is different from your regular Gmail password and is specifically for this application.

## Step 3: Configure Your Photobooth

1. Open the `.env` file in your photobooth project directory
2. Update the email settings:
   ```
   EMAIL_USERNAME=your-gmail-address@gmail.com
   EMAIL_PASSWORD=your-16-character-app-password
   ```
   Replace `your-gmail-address@gmail.com` with your actual Gmail address and `your-16-character-app-password` with the app password you generated.

## Step 4: Security Considerations

- **Never commit the `.env` file to version control** (it's already in `.gitignore`)
- The app password gives access to your Gmail account, so keep it secure
- If you suspect the app password has been compromised, revoke it immediately from your Google Account settings
- Regularly rotate app passwords for better security

## Troubleshooting

### Emails not sending

- Double-check that 2-Step Verification is enabled
- Verify the app password is correct (no spaces, exactly 16 characters)
- Ensure your Gmail address is correct in the `.env` file
- Check your spam/junk folder for test emails
- Review your server's error logs for PHPMailer-specific errors

### "Less secure app access" error

If you see this error, it means you're trying to use your regular Gmail password instead of an app password. Always use app passwords for applications.

## Alternative Email Providers

If you prefer not to use Gmail, you can configure other SMTP providers:

### Outlook/Hotmail
```
EMAIL_USERNAME=your-email@outlook.com
EMAIL_PASSWORD=your-app-password
```
Generate app passwords from your Microsoft account security settings.

### Custom SMTP Server
Modify `send_email.php` to use your own SMTP server settings:
- Change `$mail->Host` to your SMTP server
- Update `$mail->Port` if different
- Adjust authentication settings as needed

## Testing

After setup, test the email functionality by:
1. Taking some photos in the photobooth
2. Going to the edit page
3. Clicking the email button
4. Checking if you receive the email with the photo strip

## Support

If you encounter issues:
- Verify all steps above are completed correctly
- Check Google's documentation for the latest app password instructions
- Ensure your hosting provider allows outgoing SMTP connections
- Consider firewall or security software that might block email sending