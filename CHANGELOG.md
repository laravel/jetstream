# Release Notes

## [Unreleased](https://github.com/laravel/jetstream/compare/v1.4.0...master)


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
