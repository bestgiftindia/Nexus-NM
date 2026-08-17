<?php

namespace App\Enums;

enum Message: string
{
    case OTPSEND = "OTP has been sent to your email address.";
    case OTPRESEND = 'A new OTP has been sent to your email address.';
    case OTPVERIFY = 'OTP verified successfully.';
    case INVALIDOTP = 'Invalid or expired OTP.';
    case LOGOUT = 'You have been logged out successfully.';
    case LOGINNOTIFY = "Please login to continue.";

    case SOCIALMEDIAUPDATE = "Social media links updated successfully.";
    case PROFILEUPDATE = "Profile updated successfully.";

    case ROLESAVE = "Role created successfully.";
    case ROLEUPDATE = "Role updated successfully";
    case PERMISSIONSAVE = "Permission created successfully.";
    case PERMISSIONUPDATE = "Permission updated successfully";

    case USERSAVE = "User created successfully.";
    case USERUPDATE = "User updated successfully.";

    case LOSHUGRIDSAVE = "Loshugrid has been created successfully.";
    case LOSHUGRIDUPDATE = "Loshugrid has been updated successfully.";
}
