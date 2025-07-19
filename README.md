# Delete Unverified Users (Manual)

This WordPress MU-plugin helps delete a very high number of pending users that have not verified their emails.  
If your site has thousands of unverified users—usually spammers—this one-click tool will clean them up.

## What It Does

- Adds a page under **Users > Delete Unverified Users**
- Deletes users who:
  - Have `user_status = 0` (unverified)
  - Registered more than 24 hours ago
  - Have the role `subscriber`
- Logs deleted user IDs to `/wp-content/uploads/deleted-unverified-users.log`

## Installation

1. Go to `/wp-content/` in your WordPress site.
2. If the `mu-plugins` folder doesn't exist, create it.
3. Save `delete-unverified-users.php` into the `mu-plugins` folder.
4. (Optional) Save this README for reference.

## How to Use

1. In your WordPress dashboard, go to **Users > Delete Unverified Users**
2. Click the **Delete Unverified Users** button
3. Unverified users matching the criteria will be permanently deleted
4. Check `/wp-content/uploads/deleted-unverified-users.log` for a record of deleted user IDs

## Notes

- This is a manual-only tool. It does not run automatically.
- Use with caution—deleted users cannot be recovered.
- Always back up your database before performing mass deletions.

---
