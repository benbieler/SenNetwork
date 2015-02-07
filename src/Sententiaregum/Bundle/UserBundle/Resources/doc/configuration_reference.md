Configuration Reference
=======================

These are the default values of the sententiaregum user configuration

``` yaml
# app/config/config.yml

sententiaregum_user:
    api_key_authentication:
        enabled:                   false
        credential_verify_service: "sen.user.service.authentication"
        api_token_generator:       "sen.user.service.api_token_generator"
    user_registration:
        enabled:                    false
        default_registration_roles: []
    service:
        user_crud: "sen.user.service.crud"
