Sample config for hwi 0.3
================

## routing.yml

here we add the [__hwi routes__](https://github.com/hwi/HWIOAuthBundle/blob/master/Resources/doc/1-setting_up_the_bundle.md#c-import-the-routing) and the login check for hubic

    hwi_oauth_redirect:
        resource: "@HWIOAuthBundle/Resources/config/routing/redirect.xml"
        prefix:   /connect

    hwi_oauth_login:
        resource: "@HWIOAuthBundle/Resources/config/routing/login.xml"
        prefix:   /login

    hubic_login:
        pattern: /login/check-hubic

## config.yml

HWIOAuthBundle in v0.3 does not support hubic as a resource_owner, so we do add a generic oauth2 ressource.

    hwi_oauth:
        firewall_name: secured_area
        resource_owners:
            hubic:
                type:                oauth2
                client_id:           **paste your id**
                client_secret:       **paste your secret**
                authorization_url:   https://api.hubic.com/oauth/auth/
                access_token_url:    https://api.hubic.com/oauth/token
                infos_url:           https://api.hubic.com/1.0/account
                scope:               "usage.r,account.r,getAllLinks.r,credentials.r,sponsorCode.r,activate.w,sponsored.r,links.drw"
                user_response_class: HWI\Bundle\OAuthBundle\OAuth\Response\PathUserResponse
                paths:
                    identifier: email
                    nickname:   email
                    realname:   ["firstname", "lastname"]

## security.yml

these security settings are merged from a fresh sf2 installation and the [__security layer__](https://github.com/hwi/HWIOAuthBundle/blob/0.3/Resources/doc/3-configuring_the_security_layer.md) docs.

    # you can read more about security in the related section of the documentation
    # http://symfony.com/doc/current/book/security.html
    security:
        # http://symfony.com/doc/current/book/security.html#encoding-the-user-s-password
        encoders:
            Symfony\Component\Security\Core\User\User: plaintext

        # http://symfony.com/doc/current/book/security.html#hierarchical-roles
        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: [ROLE_USER, ROLE_ADMIN, ROLE_ALLOWED_TO_SWITCH]

        # http://symfony.com/doc/current/book/security.html#where-do-users-come-from-user-providers
        providers:
            in_memory:
                memory:
                    users:
                        user:  { password: userpass, roles: [ 'ROLE_USER' ] }
                        admin: { password: adminpass, roles: [ 'ROLE_ADMIN' ] }
            hwi:
              id: hwi_oauth.user.provider

        # the main part of the security, where you can set up firewalls
        # for specific sections of your app
        firewalls:
            # disables authentication for assets and the profiler, adapt it according to your needs
            dev:
                pattern:  ^/(_(profiler|wdt)|css|images|js)/
                security: false

            secured_area:
                pattern:    ^/
                #form_login:
                #    provider: fos_userbundle
                #    login_path: /connect/
                #    check_path: /login/login_check
                anonymous:    true
                oauth:
                    resource_owners:
                        hubic: "/login/check-hubic"
                    login_path: /connected/
                    failure_path: "/connected/?fail"
                    oauth_user_provider:
                        service: hwi_oauth.user.provider

        # with these settings you can restrict or allow access for different parts
        # of your application based on roles, ip, host or methods
        # http://symfony.com/doc/current/cookbook/security/access_control.html
        access_control:
            #- { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY, requires_channel: https }

