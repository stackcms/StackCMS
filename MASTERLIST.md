# Master List for Pre-Production Changes
This will be the complete list of the developers plans and suggestions as they make any progress for the script. Some features may be added in the future after further discussion.

### Front-end
- [x] Provide a default front-end layout.
- [x] Allow an admin to open or close TCG registration.
- [x] Set the levels on the default about page to reflect the database.
- [x] Allow users to choose which level badge set shows on their profile.
- [x] Any on-site contact form such as general contact, quit form and doubles exchange should be received via on-site mailbox instead of the TCG's email address.
- [x] Add a pre-prejoin system for potential members:
  - [x] A deck voting form in able to help the admin determine which decks to release for prejoin.
  - [x] A deck claims and donations form to get more decks to create for prejoin.
- [x] Allow users to show whether they accept random trades and/or let others to put trades through.
- [x] Add chatbox to the sidebar.
- [x] Add member of the week/month to the sidebar.
- [x] Add special masteries services for event and member cards.
- [x] Add number of turned in trade logs and total points redeemed.

### Admin / Back-end
- [x] Allow changing social media links via the admin panel (discord, twitter, etc)
- [x] Allow navigation bar to keep current category expanded by default - even with child pages of the ones linked. (?)
- [x] Add link back to Dashboard (index.php) - already linked via the `Dashboard` menu, can be added to `CoreAdmin` logo as well.
- [x] Allow an admin to schedule a blog post.
  - [x] Add a method of actually scheduling when the post will be released.
- [x] Allow an admin to draft a blog post.
- [x] Add an absolute path for general use via the settings database and admin panel.
- [x] Allow an admin to create/edit/delete a page content that can read PHP codes.
- [x] Allow an admin to open/close voting for member of the week/month.
  - [x] Allow an admin to choose between MOTweek or MOTmonth.
  - [x] Automatically set the member when a new week/month starts as its MOTM/MOTW.
  - [x] Add option to enable/disable MOTM/MOTW on the sidebar.
- [x] Add logs management via admin panel to edit/delete double activity/trade logs.
- [x] Add query to delete game logs from two weeks ago to weekly cron job.
- [x] Add game management to admin panel.
  - [x] Generate choice cards using the game rewards function.
  - [x] Allow an admin to create password gate games via admin panel with at least 5 rounds prepared.
- [x] Add option to enable/disable chatbox on the sidebar.
- [x] Add auto rewards function for those who pre-prejoin donated when the TCG opens for prejoin.
- [x] Ability to set card break when adding a deck.
- [x] Added a list of plugins to install/uninstall.
  - [x] Uninstall form processing for selected plugin.
  - [x] Install form processing for selected plugin.
- [x] Ability to set the amount of cards to display for Melting Pot and Card Claim.
- [x] Ability to set the amount of decks to be released and wishes to be granted per update.
- [x] Ability to set deck releases and official updates to weekly or bi-weekly (should include functions for cron jobs)

### Site-wide
- [x] Change all iterations of currencies or `cur1` and `cur2` to a variable in which the admin can adjust in the settings.
  - [x] Allow admin to list currency files via admin panel from most special to common currency.
- [ ] Allow admin to choose whether to have deck colors as a "thing".
- [x] Change game rewards to variables from game files to avoid editing the files manually.

### Features to Consider
- [x] Member decks feature.
  - [x] Add option to enable/disable member deck feature.
  - [x] Allow admin to input how many cards are there in one member deck.
  - [x] Allow admin to input/add/edit/delete the tasks to unlock cards.
  - [x] Allow members to submit their finished tasks.
  - [x] Allow an admin to review a submitted task before activating the card assigned to it.
- [ ] Shop feature for TCGs with various shop items.
  - [x] Allow admin to add/edit/delete shop catalogs and categories.
  - [x] Allow admin to add/edit/delete shop items.
  - [ ] Add shopping cart function for members.
- [ ] Built-in forum for larger TCGs.
  - [ ] Allow an admin to make it an option during installation.
- [ ] Add poll system for members.
- [x] Revert the script back to its original logs setup:
  - [x] Use only a general `user_logs` and `user_trades` database tables for all members.
