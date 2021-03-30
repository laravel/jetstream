# Release Notes

## [Unreleased](https://github.com/laravel/jetstream/compare/v2.3.0...master)


## [v2.3.0 (2021-03-30)](https://github.com/laravel/jetstream/compare/v2.2.5...v2.3.0)

### Added
- Add section title aside slot ([#727](https://github.com/laravel/jetstream/pull/727))

### Changed
- Remove unnecessary CSS classes ([#735](https://github.com/laravel/jetstream/pull/735))
- Accept any type of response in RedirectsActions trait ([#740](https://github.com/laravel/jetstream/pull/740))

### Fixed
- Fixed the test that's failing in EmailVerificationTest ([#737](https://github.com/laravel/jetstream/pull/737), [e0a9d2e](https://github.com/laravel/jetstream/commit/e0a9d2e4d504c88d069691ee82d803801badb641))
- Fixes an error when sending email invitations when Fortify's registration feature is turned off ([#741](https://github.com/laravel/jetstream/pull/741))


## [v2.2.5 (2021-03-25)](https://github.com/laravel/jetstream/compare/v2.2.4...v2.2.5)

### Fixed
- Revert "Reduce amount of queries for Teams feature" ([#729](https://github.com/laravel/jetstream/pull/729))


## [v2.2.4 (2021-03-23)](https://github.com/laravel/jetstream/compare/v2.2.3...v2.2.4)

### Changed
- Test team features only when active ([#698](https://github.com/laravel/jetstream/pull/698))
- Reduce amount of queries for Teams feature ([#708](https://github.com/laravel/jetstream/pull/708))
- Add a tag dropdown link ([#711](https://github.com/laravel/jetstream/pull/711))

### Fixed
- Button Disabled opacity-25 ([#712](https://github.com/laravel/jetstream/pull/712))
- Add scroll-region to modal ([#724](https://github.com/laravel/jetstream/pull/724))
- Load modal slot on show ([#723](https://github.com/laravel/jetstream/pull/723))

### Removed
- Don't require autoprefixer ([#714](https://github.com/laravel/jetstream/pull/714))


## [v2.2.3 (2021-03-02)](https://github.com/laravel/jetstream/compare/v2.2.2...v2.2.3)

### Fixed
- Update mimes ([90b6d1b](https://github.com/laravel/jetstream/commit/90b6d1b5ddb02a10f4698428e02c204962a9d16a))
- Prevent network request when pressing escape to close modal ([#695](https://github.com/laravel/jetstream/pull/695))


## [v2.2.2 (2021-02-23)](https://github.com/laravel/jetstream/compare/v2.2.1...v2.2.2)

### Changed
- Use cancel wording ([9b7dbb4e](https://github.com/laravel/jetstream/commit/9b7dbb4ea10aa7a5b678695719f7f1bc820b61ec))
- Use inertia progress ([c1f1fa0](https://github.com/laravel/jetstream/commit/c1f1fa0ab964f3b9dbe144eb60e18d38e835b195))
- Remove '.native' deprecated modifier in vue3 ([#689](https://github.com/laravel/jetstream/pull/689))

### Fixed
- Require password ([b24ef0c](https://github.com/laravel/jetstream/commit/b24ef0ceea656adc71367c1d64190626f27e8c1e))


## [v2.2.1 (2021-02-16)](https://github.com/laravel/jetstream/compare/v2.2.0...v2.2.1)

### Changed
- Add unverified state to `UserFactory` ([#679](https://github.com/laravel/jetstream/pull/679))
  
### Fixed
- Update purge settings ([36c4685](https://github.com/laravel/jetstream/commit/36c46858b2dfd0b6b001872ce4d682a31221bb49))


## [v2.2.0 (2021-02-16)](https://github.com/laravel/jetstream/compare/v2.1.4...v2.2.0)

### Added
- Vue v3.0 support ([#666](https://github.com/laravel/jetstream/pull/666))

### Fixed
- Fix ownsTeam when applied to non-User model ([#673](https://github.com/laravel/jetstream/pull/673))
- Allow UpdateTeamNameForm in Livewire to hydrate all Team attributes ([#676](https://github.com/laravel/jetstream/pull/676))
- Change "Logout" text to "Log out" and "Login" to "Log in" ([#677](https://github.com/laravel/jetstream/pull/677))


## [v2.1.4 (2021-02-09)](https://github.com/laravel/jetstream/compare/v2.1.3...v2.1.4)

### Changed
- Allow custom classes on the dropdown class ([#661](https://github.com/laravel/jetstream/pull/661))

### Fixed
- Fix permissions for team create routes ([#669](https://github.com/laravel/jetstream/pull/669))
- Fix overflow hidden issue with overlaping elements inside form section ([#668](https://github.com/laravel/jetstream/pull/668))


## [v2.1.3 (2021-02-02)](https://github.com/laravel/jetstream/compare/v2.1.2...v2.1.3)

### Changed
- Add `profile_photo_disk` config option ([#658](https://github.com/laravel/jetstream/pull/658), [a6245c8](https://github.com/laravel/jetstream/commit/a6245c84adc2ea209a34051ff543ccc302a275e8))


## [v2.1.2 (2021-01-26)](https://github.com/laravel/jetstream/compare/v2.1.1...v2.1.2)

### Changed
- Enabling Github Flavored Markdown for Terms and Privacy Policy ([#645](https://github.com/laravel/jetstream/pull/645))

### Fixed
- Hide AppLayout header if no slot defined in Page component ([#650](https://github.com/laravel/jetstream/pull/650), [523399f](https://github.com/laravel/jetstream/commit/523399f8496d49a6d6b6a8955b87cda0786be31b))
- Use custom team member model ([#651](https://github.com/laravel/jetstream/pull/651))
- Use email input type ([#653](https://github.com/laravel/jetstream/pull/653))


## [v2.1.1 (2021-01-19)](https://github.com/laravel/jetstream/compare/v2.1.0...v2.1.1)

### Fixed
- Fix RegistrationTest for Terms of Services ([#632](https://github.com/laravel/jetstream/pull/632))
- Add z-index to the modal ([#633](https://github.com/laravel/jetstream/pull/633))
- Skip DeleteAccountTest if feature is not enabled ([#641](https://github.com/laravel/jetstream/pull/641))
- Fix unique email on email invitations ([593e763](https://github.com/laravel/jetstream/commit/593e763f2eb07a7bdf7206fa497c22c5cb9c2938#), [a394153](https://github.com/laravel/jetstream/commit/a3941534ae9299a38a68bc2ad4f335b73861d43e))


## [v2.1.0 (2021-01-12)](https://github.com/laravel/jetstream/compare/v2.0.3...v2.1.0)

### Added
- Add `--composer` option to the install command ([#607](https://github.com/laravel/jetstream/pull/607))

### Changed
- Automatically select API token when it's created ([#589](https://github.com/laravel/jetstream/pull/589))
- Change current team of user when creating a new additional team ([#618](https://github.com/laravel/jetstream/pull/618))
- Make text translatable in confirm-password livewire view ([#628](https://github.com/laravel/jetstream/pull/628))

### Fixed
- Banner should be hidden in default ([#599](https://github.com/laravel/jetstream/pull/599))
- Jetstream ignoring sessions from session connection in config ([#630](https://github.com/laravel/jetstream/pull/630))


## [v2.0.3 (2021-01-06)](https://github.com/laravel/jetstream/compare/v2.0.2...v2.0.3)

### Added
- Allow publishing of missing Inertia files ([#596](https://github.com/laravel/jetstream/pull/596), [b28ee67](https://github.com/laravel/jetstream/commit/b28ee670590312d014448c2c8f2479ec264c64cc))

### Changed
- Make button text in team invitation email translatable ([#598](https://github.com/laravel/jetstream/pull/598))

### Removed
- Remove unused Vue components ([#596](https://github.com/laravel/jetstream/pull/596))

### Fixed
- Make a similar footer section on welcome page ([#602](https://github.com/laravel/jetstream/pull/602))


## [v2.0.2 (2021-01-06)](https://github.com/laravel/jetstream/compare/v2.0.1...v2.0.2)

### Fixed
- Fix test ([d24c41b](https://github.com/laravel/jetstream/commit/d24c41bca5fd69fa369915565a18ac2510b04928))


## [v2.0.1 (2021-01-06)](https://github.com/laravel/jetstream/compare/v2.0.0...v2.0.1)

### Changed
- Make cancel link translatable ([#592](https://github.com/laravel/jetstream/pull/592))

### Fixed
- Inertia Stack Authentication Page logo link update with inertia-link ([#594](https://github.com/laravel/jetstream/pull/594))


## [v2.0.0 (2021-01-05)](https://github.com/laravel/jetstream/compare/v1.6.6...v2.0.0)

A major new Jetstream release. Check out [the announcement post](https://blog.laravel.com/jetstream-20-released).


## [v1.6.6 (2021-01-05)](https://github.com/laravel/jetstream/compare/v1.6.5...v1.6.6)

### Changed
- Update to Laravel Mix v6 ([#582](https://github.com/laravel/jetstream/pull/582))

### Fixed
- Avoids eqeqeq errors in eslint ([#580](https://github.com/laravel/jetstream/pull/580))


## [v1.6.5 (2020-12-15)](https://github.com/laravel/jetstream/compare/v1.6.4...v1.6.5)

### Fixed
- Add missing import must verify email interface ([#529](https://github.com/laravel/jetstream/pull/529))
- Use named route for two-factor ([#528](https://github.com/laravel/jetstream/pull/528))


## [v1.6.4 (2020-12-08)](https://github.com/laravel/jetstream/compare/v1.6.3...v1.6.4)

### Changed
- Rename path to prefix for route config ([#504](https://github.com/laravel/jetstream/pull/504))


## [v1.6.3 (2020-12-01)](https://github.com/laravel/jetstream/compare/v1.6.2...v1.6.3)

### Fixed
- Clears any validation error message before re-displaying confirmation ([#487](https://github.com/laravel/jetstream/pull/487))


## [v1.6.2 (2020-11-24)](https://github.com/laravel/jetstream/compare/v1.6.1...v1.6.2)

### Fixed
- Use Alpine directly for guest layout ([#485](https://github.com/laravel/jetstream/pull/485))


## [v1.6.1 (2020-11-17)](https://github.com/laravel/jetstream/compare/v1.6.0...v1.6.1)

### Changed
- Use mix helper and remove CDNs  ([#450](https://github.com/laravel/jetstream/pull/450), [ca94bd5](https://github.com/laravel/jetstream/commit/ca94bd599f9874cf02b9e92ae4d91605fdc1441b))

### Fixed
- Fix feature toggles in Inertia ([#451](https://github.com/laravel/jetstream/pull/451), [#454](https://github.com/laravel/jetstream/pull/454))
- Update response status code to prevent method not allowed ([#456](https://github.com/laravel/jetstream/pull/456))


## [v1.6.0 (2020-11-03)](https://github.com/laravel/jetstream/compare/v1.5.3...v1.6.0)

### Added
- PHP 8 Support ([#416](https://github.com/laravel/jetstream/pull/416))

### Fixed
- Delete user tokens when user is deleted ([#406](https://github.com/laravel/jetstream/pull/406), [5bc500e](https://github.com/laravel/jetstream/commit/5bc500e65c69f9bee8667de9287d18369b24b793))
- Fix composer memory limit crashes ([#412](https://github.com/laravel/jetstream/pull/412))
- Update `TwoFactorAuthenticationForm.vue` ([#417](https://github.com/laravel/jetstream/pull/417))


## [v1.5.3 (2020-10-27)](https://github.com/laravel/jetstream/compare/v1.5.2...v1.5.3)

### Fixed
- Fix Livewire Modal Closing Issue By Bumping Alpine To `2.7.3` ([#404](https://github.com/laravel/jetstream/pull/404))
- Delete the users profile picture ([#399](https://github.com/laravel/jetstream/pull/399))


## [v1.5.2 (2020-10-27)](https://github.com/laravel/jetstream/compare/v1.5.1...v1.5.2)

### Added
- Adds custom redirects after creating or deleting a team ([#383](https://github.com/laravel/jetstream/pull/383), [ef3382d](https://github.com/laravel/jetstream/commit/ef3382d316b763211f25eaa7e1ba09ec373d0508))

### Changed
- Make team show use policies ([#379](https://github.com/laravel/jetstream/pull/379))
- Use ziggy current method to check active link ([#402](https://github.com/laravel/jetstream/pull/402))

### Fixed
- Add missing trait ([#385](https://github.com/laravel/jetstream/pull/385))
- Slot Empty Checking & Return Default Value ([#389](https://github.com/laravel/jetstream/pull/389))
- Constrain Ziggy version to `^0.9.4` ([#395](https://github.com/laravel/jetstream/pull/395))


## [v1.5.1 (2020-10-21)](https://github.com/laravel/jetstream/compare/v1.5.0...v1.5.1)

### Fixed
- Lock `inertiajs/inertia-laravel` to `^0.2.4` ([#371](https://github.com/laravel/jetstream/pull/371))


## [v1.5.0 (2020-10-20)](https://github.com/laravel/jetstream/compare/v1.4.1...v1.5.0)

### Added
- Add Jetstream route prefix config ([#362](https://github.com/laravel/jetstream/pull/362))

### Changed
- Added js directory path alias ([#328](https://github.com/laravel/jetstream/pull/328), [412e748](https://github.com/laravel/jetstream/commit/412e748649805ac32a50c01273bf073ef4b2aa6f))
- Update wording ([055be85](https://github.com/laravel/jetstream/commit/055be8529b4fb45897e9d5ef42eee0b2a45c8bde))


## [v1.4.1 (2020-10-13)](https://github.com/laravel/jetstream/compare/v1.4.0...v1.4.1)

### Changed
- Give user object to update profile information form ([#324](https://github.com/laravel/jetstream/pull/324), [d818b31](https://github.com/laravel/jetstream/commit/d818b31784bb29faaab6abefd3e7aab9a5d4dce1))
- Numeric keyboard in Two Factor Challenge ([#336](https://github.com/laravel/jetstream/pull/336))

### Fixed
- Correct visibility of Fortify features in profile view ([#322](https://github.com/laravel/jetstream/pull/322))
- Fix incorrect variable ([afc89ad](https://github.com/laravel/jetstream/commit/afc89ad834d179dd39a0b86815b48dac8f039f69))


## [v1.4.0 (2020-10-06)](https://github.com/laravel/jetstream/compare/v1.3.2...v1.4.0)

### Changed
- Extract switch team logic into HasTeams trait ([#312](https://github.com/laravel/jetstream/pull/312))
- Use named routes on Inertia with Ziggy ([#314](https://github.com/laravel/jetstream/pull/314), [#316](https://github.com/laravel/jetstream/pull/316))
- Bump Tailwind & Inertia ([#315](https://github.com/laravel/jetstream/pull/315))


## [v1.3.2 (2020-10-05)](https://github.com/laravel/jetstream/compare/v1.3.1...v1.3.2)

### Changed
- Add dependency-less modal focus trapping ([#294](https://github.com/laravel/jetstream/pull/294))
- Use `getAuthIdentifier` instead of `getKey` ([#283](https://github.com/laravel/jetstream/pull/283))
- Ensure text remains visible during webfont load ([#290](https://github.com/laravel/jetstream/pull/290))
- Make terms translatable ([#300](https://github.com/laravel/jetstream/pull/300))
- Disable profile photo and api by default ([096d7dd](https://github.com/laravel/jetstream/commit/096d7dd6e4cbbfb67206ce8c03befbdfbec0595e))

### Fixed
- a11y(auth): associate inputs with labels ([#285](https://github.com/laravel/jetstream/pull/285))


## [v1.3.1 (2020-09-29)](https://github.com/laravel/jetstream/compare/v1.3.0...v1.3.1)

### Changed
- Bump Alpine.js version up to 2.7.0 ([#262](https://github.com/laravel/jetstream/pull/262))

### Fixed
- Use session table from the config ([#265](https://github.com/laravel/jetstream/pull/265))
- Add `:key` buildings for list (for) rendering to `TeamMemberManager.vue` ([#270](https://github.com/laravel/jetstream/pull/270))
- Add Submenu Style For When Profile Photos Are Unmanaged ([#275](https://github.com/laravel/jetstream/pull/275), [e717c9e](https://github.com/laravel/jetstream/commit/e717c9e89d5757e12167fa0b729657ecae12fd61))
- Disable save button while profile pic is uploading ([#266](https://github.com/laravel/jetstream/pull/266))


## [v1.3.0 (2020-09-22)](https://github.com/laravel/jetstream/compare/v1.2.1...v1.3.0)

### Added
- Added hasTeamRole check on the HasTeams trait ([#251](https://github.com/laravel/jetstream/pull/251), [87b4bf3](https://github.com/laravel/jetstream/commit/87b4bf356387a66f87a90712f0aa4a1ce3c13126))

### Changed
- More translation support ([#211](https://github.com/laravel/jetstream/pull/211), [#216](https://github.com/laravel/jetstream/pull/216))
- Use `mix()` instead of `asset()` for hot-reload support ([#217](https://github.com/laravel/jetstream/pull/217))
- Remove Hard Coded Livewire Routes ([#226](https://github.com/laravel/jetstream/pull/226))
- Update `UpdateUserProfileInformation.php` ([#247](https://github.com/laravel/jetstream/pull/247))

### Fixed
- Fixed attribute order inside views ([#207](https://github.com/laravel/jetstream/pull/207))
- Update modal z-index ([#212](https://github.com/laravel/jetstream/pull/212))
- Add key of v-for teams ([#239](https://github.com/laravel/jetstream/pull/239))
- Correct ID value for email field ([#240](https://github.com/laravel/jetstream/pull/240))
- Fix purging of CSS classes on production builds ([#249](https://github.com/laravel/jetstream/pull/249))
- Fix migration ([1883071](https://github.com/laravel/jetstream/commit/1883071d771193e59ef7ed4509576e9b853dac43))


## [v1.2.1 (2020-09-15)](https://github.com/laravel/jetstream/compare/v1.2.0...v1.2.1)

### Added
- Add delete profile photo button ([#110](https://github.com/laravel/jetstream/pull/110), [785dcdfa](https://github.com/laravel/jetstream/commit/785dcdfa2a4aa839b7638969ee58dbe3eb763980), [69f5c8d](https://github.com/laravel/jetstream/commit/69f5c8d35a76a811ac3e51749e788be51792a107), [c51e5cf](https://github.com/laravel/jetstream/commit/c51e5cf6ad41c695b62bf10304a7c20a62dddafe))

### Fixed
- Don't include update password form if feature disabled ([#197](https://github.com/laravel/jetstream/pull/197))
- Remove hard coded routes and use `route()` helper by name instead ([#203](https://github.com/laravel/jetstream/pull/203))
- Fix button margins on small screens ([#206](https://github.com/laravel/jetstream/pull/206))


## [v1.2.0 (2020-09-14)](https://github.com/laravel/jetstream/compare/v1.1.2...v1.2.0)

### Added
- Confirmable Support ([#196](https://github.com/laravel/jetstream/pull/196), [c1ff4a5](https://github.com/laravel/jetstream/commit/c1ff4a55fccf674958a3b0ef5ec97cea81ad4501))

### Changed
- Update Fortify Actions to use PasswordValidationRules Trait ([#168](https://github.com/laravel/jetstream/pull/168))
- Make labels translatable in livewire views ([#177](https://github.com/laravel/jetstream/pull/177))
- A few more strings that should be translatable ([#192](https://github.com/laravel/jetstream/pull/192))

### Fixed
- Refresh navigation dropdown when teams/profile forms save ([#132](https://github.com/laravel/jetstream/pull/132))
- Fix API token deletion bug ([#155](https://github.com/laravel/jetstream/pull/155))
- Fix profile picture distortion for team owners ([#165](https://github.com/laravel/jetstream/pull/165))
- Fix HasTeams trait to be able to use queries ([#173](https://github.com/laravel/jetstream/pull/173))


## [v1.1.2 (2020-09-10)](https://github.com/laravel/jetstream/compare/v1.1.1...v1.1.2)

### Changed
- Set fully url forget and reset password action ([#136](https://github.com/laravel/jetstream/pull/136))

### Fixed
- Missing Closing Parenthesis ([#140](https://github.com/laravel/jetstream/pull/140))


## [v1.1.1 (2020-09-10)](https://github.com/laravel/jetstream/compare/v1.1.0...v1.1.1)

### Fixed
- Fix missing request ([#137](https://github.com/laravel/jetstream/pull/137))


## [v1.1.0 (2020-09-10)](https://github.com/laravel/jetstream/compare/v1.0.2...v1.1.0)

### Added
- Add alt attribute/value to image tag ([#106](https://github.com/laravel/jetstream/pull/106))
- Translates more strings ([#101](https://github.com/laravel/jetstream/pull/101))

### Changed
- Use static properties for Team and Membership too ([#119](https://github.com/laravel/jetstream/pull/119), [#121](https://github.com/laravel/jetstream/pull/121))
- Add inertia render hooks ([b2e5729](https://github.com/laravel/jetstream/commit/b2e57294ada600164f5c098b5e0abd1e38f89133), [fd76d88](https://github.com/laravel/jetstream/commit/fd76d8816964388edb4aad988fa9b0659b2e89c7), [4929383](https://github.com/laravel/jetstream/commit/4929383bd98f49885059ec63a702f17b34bc90ce))

### Fixed
- Fix for `vendor:publish` routes ([#109](https://github.com/laravel/jetstream/pull/109))
- Make team menu entry "truncate" ([#114](https://github.com/laravel/jetstream/pull/114))
- Added object-cover to profile images ([#122](https://github.com/laravel/jetstream/pull/122))
- Inertia: Use check instead of authorize for canCreateTeams ([#129](https://github.com/laravel/jetstream/pull/129))


## [v1.0.2 (2020-09-08)](https://github.com/laravel/jetstream/compare/v1.0.1...v1.0.2)

### Changed
- Update sessions table ([49a6815](https://github.com/laravel/jetstream/commit/49a6815c7f0e5bb18261c7a0df86a3799328f3b3))
- Fix `ownsTeam` check ([65ce882](https://github.com/laravel/jetstream/commit/65ce8824a8a5a631a9cc02595655b8699bf4b086))


## [v1.0.1 (2020-09-08)](https://github.com/laravel/jetstream/compare/v1.0.0...v1.0.1)

### Changed
- Update to Laravel Fortify 1.0 ([#96](https://github.com/laravel/jetstream/pull/96))


## [v1.0.0 (2020-09-08)](https://github.com/laravel/jetstream/compare/v0.7.3...v1.0.0)

### Fixed
- Fix modal ([47ad018](https://github.com/laravel/jetstream/commit/47ad018841941bf054d1502f36392f5a817fe4f4))


## [v0.7.3 (2020-09-08)](https://github.com/laravel/jetstream/compare/v0.7.2...v0.7.3)

### Fixed
- Fix bugs with method names ([d85870d](https://github.com/laravel/jetstream/commit/d85870dad5f57d565a1534746c9158debc7b4af1), [54a3db6](https://github.com/laravel/jetstream/commit/54a3db67735df017ceab8f1aec6b3a37218edb42))


## [v0.7.2 (2020-09-08)](https://github.com/laravel/jetstream/compare/v0.7.1...v0.7.2)

### Fixed
- Revert layout changes ([915c797](https://github.com/laravel/jetstream/commit/915c797fdc345822af188ba044e42997ec577419))


## [v0.7.1 (2020-09-08)](https://github.com/laravel/jetstream/compare/v0.7.0...v0.7.1)

### Added
- Implement TeamPolicy::create authorization check before creating a team ([#82](https://github.com/laravel/jetstream/pull/82), [42ed0aa](https://github.com/laravel/jetstream/commit/42ed0aabfabc2fa6ae741ef2b67f936c598fd05f))
- Provide a way of customizing/disabling the default routes ([#67](https://github.com/laravel/jetstream/pull/67))
- Delete User password confirmation ([#91](https://github.com/laravel/jetstream/pull/91))

### Changed
- Make auth views translatable ([#81](https://github.com/laravel/jetstream/pull/81))

### Fixed
- Fix API vs Web TeamsHasPermissions, add Tests ([#89](https://github.com/laravel/jetstream/pull/89))
- Fix "remember me" checkbox ([#86](https://github.com/laravel/jetstream/pull/86))
- Fix `InstallCommand.php` ([#55](https://github.com/laravel/jetstream/pull/55))


## [v0.7.0 (2020-09-06)](https://github.com/laravel/jetstream/compare/v0.6.4...v0.7.0)

### Changed
- Set fully qualified URL for login,register and logout ([#49](https://github.com/laravel/jetstream/pull/49))
- Use application language on layouts ([#58](https://github.com/laravel/jetstream/pull/58))
- Use incrementing ids for team ids ([71b67f1](https://github.com/laravel/jetstream/commit/71b67f1e04f6c06741b910ff999aa6370b3ba970))


## [v0.6.4 (2020-09-04)](https://github.com/laravel/jetstream/compare/v0.6.3...v0.6.4)

### Fixed
- Fix disk ([f00615f](https://github.com/laravel/jetstream/commit/f00615f26d59e7adf16c889207c054ab079f462a))


## [v0.6.3 (2020-09-04)](https://github.com/laravel/jetstream/compare/v0.6.2...v0.6.3)

### Changed
- Update photo storage ([b304f4e](https://github.com/laravel/jetstream/commit/b304f4ec3ccd7052317393f0655bc4a6e3f7b8ef))


## [v0.6.2 (2020-09-04)](https://github.com/laravel/jetstream/compare/v0.6.1...v0.6.2)

### Changed
- Change how auth guards are setup ([6ac870f](https://github.com/laravel/jetstream/commit/6ac870f0af7aee4e6cf835771093e8466576cca2))


## [v0.6.1 (2020-09-04)](https://github.com/laravel/jetstream/compare/v0.6.0...v0.6.1)

### Changed
- Set a fully qualified URL for css file ([#27](https://github.com/laravel/jetstream/pull/27))
- Allow using Inertia without a session ([#43](https://github.com/laravel/jetstream/pull/43))
- Make error message translatable ([#39](https://github.com/laravel/jetstream/pull/39))


## [v0.6.0 (2020-09-04)](https://github.com/laravel/jetstream/compare/v0.0.5...v0.6.0)

### Changed
- Support Livewire V2 ([#23](https://github.com/laravel/jetstream/pull/23), [b99cbd7](https://github.com/laravel/jetstream/commit/b99cbd727b1f247f9b47937b3c87de0f863e46f9))


## [v0.0.5 (2020-09-02)](https://github.com/laravel/jetstream/compare/v0.0.4...v0.0.5)

### Changed
- Change divs to buttons ([#8](https://github.com/laravel/jetstream/pull/8))
- Replace the "home" route by the "dashboard" route ([#16](https://github.com/laravel/jetstream/pull/16))
- Include autocomplete on all relevant elements ([#13](https://github.com/laravel/jetstream/pull/13))
- Configure Purgecss for Tailwind ([#10](https://github.com/laravel/jetstream/pull/10), [af9bba5](https://github.com/laravel/jetstream/commit/af9bba58050c53b4b339178d08129ba105e861d0))
- Use numeric ids for users ([#22](https://github.com/laravel/jetstream/pull/22))


## [v0.0.4 (2020-09-01)](https://github.com/laravel/jetstream/compare/v0.0.3...v0.0.4)

### Changed
- Use inertia link ([991457c](https://github.com/laravel/jetstream/commit/991457c8858090c2d661b33cbb8a0a7cfa73ab45))


## [v0.0.3 (2020-09-01)](https://github.com/laravel/jetstream/compare/v0.0.2...v0.0.3)

### Changed
- Update guard usage ([e871b88](https://github.com/laravel/jetstream/commit/e871b887e01f71c347aae41c2a8d756b4d59a42e))


## [v0.0.2 (2020-09-01)](https://github.com/laravel/jetstream/compare/v0.0.1...v0.0.2)

### Changed
- Require Sanctum 2.6 ([#2](https://github.com/laravel/jetstream/pull/2))


## v0.0.1 (2020-08-31)

Initial release.
