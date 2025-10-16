# Moderation Dashboard Plan

## Overview
Admin interface for managing user-generated content (primarily usernames) on leaderboards and public displays.

## Current State
- Basic username filtering exists in leaderboards
- No admin interface for moderation
- No flagging system
- Manual database queries required for moderation

## Requirements

### Access Control
- **Admin middleware**: Protect moderation routes
- **Admin gate**: Check if user is admin (via config or database flag)
- **Admin users table**: Add `is_admin` boolean to users table, or separate admins table

### Core Features

#### 1. Moderation Dashboard
**Route**: `/admin/moderation`

**Layout**:
- Table of all users with usernames
- Columns: Username, GitHub URL, Total Plays, Total Winnings, Status, Actions
- Search/filter by username
- Pagination
- Tabs: All / Flagged / Banned / Hidden

#### 2. User States
Three moderation states:
- **Active** (default): Normal user, shows everywhere
- **Hidden**: Username hidden from leaderboards/public displays (shows as "Anonymous")
- **Banned**: Cannot submit new plays (API rejects), hidden from public

#### 3. Moderation Actions
Quick action buttons per user:
- **Hide Username**: Hide from leaderboards but allow plays
- **Ban User**: Block new plays + hide username
- **Approve**: Explicitly mark as reviewed/safe
- **Flag**: Mark for review (manual or auto)

#### 4. Auto-Flagging System
Automatically flag usernames containing:
- Common offensive words (configurable list)
- Special character spam (e.g., "████████")
- Impersonation attempts (e.g., "admin", "moderator")
- Length violations (too long/short)

**Implementation**:
- Run on user creation or username change
- Queue job to check against rules
- Add to `flagged_usernames` table if matches

#### 5. Moderation Log
Audit trail of all moderation actions:
- Who performed action
- What action (hide/ban/approve/flag)
- When (timestamp)
- Reason (optional text field)
- User affected

## Database Schema

### New Tables

#### `flagged_usernames`
```sql
id, user_id, reason, flagged_at, reviewed_at, reviewed_by, status (pending/approved/rejected)
```

#### `moderation_logs`
```sql
id, admin_user_id, target_user_id, action (hide/ban/approve/flag), reason, created_at
```

### Modified Tables

#### `users` - Add columns:
```sql
is_admin (boolean, default false)
moderation_status (enum: active/hidden/banned, default active)
moderation_reason (text, nullable)
moderated_at (timestamp, nullable)
moderated_by (foreign key to users.id, nullable)
```

## UI/UX Design

### Dashboard Layout (Livewire Component)
```
╔═══════════════════════════════════════════════════════════╗
║  MODERATION DASHBOARD                                     ║
╠═══════════════════════════════════════════════════════════╣
║  [All] [Flagged] [Hidden] [Banned]                       ║
║                                                           ║
║  Search: [____________]  Status: [All ▼]                 ║
║                                                           ║
║  ┌─────────────────────────────────────────────────────┐ ║
║  │ Username    │ Plays │ Winnings │ Status  │ Actions │ ║
║  ├─────────────────────────────────────────────────────┤ ║
║  │ @cooluser   │ 123   │ +4,500   │ Active  │ [Hide]  │ ║
║  │ @badword123 │ 5     │ -10      │ Flagged │ [Ban]   │ ║
║  │ @spammer    │ 1     │ 0        │ Hidden  │ [Show]  │ ║
║  └─────────────────────────────────────────────────────┘ ║
║                                                           ║
║  [1] [2] [3] ... [15]                            45 users║
╚═══════════════════════════════════════════════════════════╝
```

### Modal for Action Confirmation
```
╔══════════════════════════════════════╗
║  Ban User?                           ║
╠══════════════════════════════════════╣
║  Username: @badword123               ║
║                                      ║
║  Reason (optional):                  ║
║  [_____________________________]     ║
║                                      ║
║  This will:                          ║
║  • Hide username from leaderboards   ║
║  • Block future play submissions     ║
║                                      ║
║  [Cancel]  [Confirm Ban]             ║
╚══════════════════════════════════════╝
```

## Implementation Steps

### Phase 1: Database & Models
1. Create migration for `users` moderation columns
2. Create `flagged_usernames` migration and model
3. Create `moderation_logs` migration and model
4. Add relationships to User model

### Phase 2: Admin Access Control
1. Create `AdminMiddleware`
2. Add `isAdmin()` gate to `AppServiceProvider`
3. Add admin routes in `web.php`
4. Seed initial admin user (you!)

### Phase 3: Moderation Dashboard UI
1. Create `Livewire/Admin/ModerationDashboard.php` component
2. Create blade view with terminal styling
3. Add search/filter functionality
4. Add pagination
5. Add tab filtering (All/Flagged/Hidden/Banned)

### Phase 4: Moderation Actions
1. Add action methods to component (hide/ban/approve)
2. Create moderation log entries for each action
3. Add confirmation modals
4. Add success/error notifications

### Phase 5: Auto-Flagging System
1. Create `AutoFlagUsername` job
2. Create offensive words config/database
3. Dispatch job on user creation/update
4. Test with various usernames

### Phase 6: Leaderboard Integration
1. Update leaderboard queries to respect `moderation_status`
2. Show "Anonymous" for hidden users
3. Exclude banned users entirely
4. Test all leaderboard views

### Phase 7: API Integration
1. Update `/api/play` endpoint to check ban status
2. Return 403 for banned users
3. Add helpful error message
4. Test CLI with banned user

## Testing Strategy

### Unit Tests
- User model scopes (active, hidden, banned)
- Auto-flagging logic
- Moderation action methods

### Feature Tests
- Admin middleware blocks non-admins
- Moderation dashboard loads for admins
- Hide/ban/approve actions work correctly
- Banned users can't submit plays
- Hidden users show as "Anonymous"
- Moderation logs created correctly

### Manual Testing
- Test offensive username detection
- Test moderation UI workflows
- Test leaderboard displays
- Test CLI with moderated users

## Security Considerations

1. **Admin verification**: Only trusted users should be admins
2. **Rate limiting**: Prevent moderation action spam
3. **Audit logging**: All actions must be logged
4. **Reversible actions**: Allow unbanning/unhiding
5. **No data deletion**: Soft deletes only, keep history

## Open Questions

1. Should we email users when they're moderated?
2. Should users be able to appeal bans?
3. Should we have timed bans (e.g., 7-day suspension)?
4. Should we show moderation reason to affected users?
5. Should we have moderator roles vs full admin?
6. What's the offensive words list? (configurable via admin panel?)

## Future Enhancements

- User reporting system (let users flag others)
- Automated pattern detection (ML for toxicity)
- Moderator dashboard stats (actions per day, etc.)
- Bulk actions (ban multiple users at once)
- CSV export of flagged users
- Moderation API for automation
- Discord/Slack notifications for new flags

## Timeline Estimate

- Phase 1-2: 2-3 hours (database + auth)
- Phase 3-4: 3-4 hours (UI + actions)
- Phase 5-6: 2-3 hours (auto-flag + leaderboards)
- Phase 7: 1-2 hours (API updates)
- Testing: 2-3 hours

**Total**: ~10-15 hours of development time

## Next Session TODO

1. Start with Phase 1: Create migrations for moderation columns
2. Add `is_admin` to your user account in production
3. Set up admin routes and middleware
4. Begin building Livewire component

---

**Status**: Planning Complete ✅
**Ready to implement**: Yes
**Blocked by**: Nothing
