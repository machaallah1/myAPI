import { icon } from "@/views/demos/components/badge/demoCodeBadge";

export default [
    { heading: 'Blog' },

    {
        title: 'Posts',
        icon: { icon: 'tabler-article' },
        children: [
            { 
                title: 'List',
                icon: { icon: 'tabler-list' }, 
                to: 'apps-ecommerce-product-list'
            },
            { 
                title: 'Add', 
                icon: { icon: 'tabler-plus' },
                to: 'apps-ecommerce-product-add' 
            },
        ],
    },
    {
        title: 'Category',
        icon: { icon: 'tabler-category' },
        to: 'apps-ecommerce-product-category-list',
    },
    {
        title: 'Tags',
        icon: { icon: 'tabler-tag' },
        to: '',
    },

    {
        title: 'User',
        icon: { icon: 'tabler-user' },
        children: [
            { title: 'List', to: 'apps-user-list' },
            { title: 'View', to: { name: 'apps-user-view-id', params: { id: 21 } } },
        ],
    },
    {
        title: 'User Profile',
        icon: { icon: 'tabler-user' },
        to: {
            name: 'pages-user-profile-tab',
            params: { tab: 'profile' }
        }
    },
    {
        title: 'Account Settings',
        icon: { icon: 'tabler-settings' },
        to: {
            name: 'pages-account-settings-tab',
            params: { tab: 'account' }
        }
    },
    {
        title: 'Roles & Permissions',
        icon: { icon: 'tabler-lock' },
        children: [
            { 
                title: 'Roles', 
                icon: { icon: 'tabler-users' },
                to: 'apps-roles',
             },
            { 
                title: 'Permissions', 
                icon: { icon: 'tabler-lock' },
                to: 'apps-permissions',
             },
        ],
    },
    {
        title: 'Authentication',
        icon: { icon: 'tabler-shield-lock' },
        children: [
            {
                title: 'Login',
                children: [
                    { title: 'Login v1', to: 'pages-authentication-login-v1', target: '_blank' },
                    { title: 'Login v2', to: 'pages-authentication-login-v2', target: '_blank' },
                ],
            },
            {
                title: 'Register',
                children: [
                    { title: 'Register v1', to: 'pages-authentication-register-v1', target: '_blank' },
                    { title: 'Register v2', to: 'pages-authentication-register-v2', target: '_blank' },
                    { title: 'Register Multi-Steps', to: 'pages-authentication-register-multi-steps', target: '_blank' },
                ],
            },
            {
                title: 'Verify Email',
                children: [
                    { title: 'Verify Email v1', to: 'pages-authentication-verify-email-v1', target: '_blank' },
                    { title: 'Verify Email v2', to: 'pages-authentication-verify-email-v2', target: '_blank' },
                ],
            },
            {
                title: 'Forgot Password',
                children: [
                    { title: 'Forgot Password v1', to: 'pages-authentication-forgot-password-v1', target: '_blank' },
                    { title: 'Forgot Password v2', to: 'pages-authentication-forgot-password-v2', target: '_blank' },
                ],
            },
            {
                title: 'Reset Password',
                children: [
                    { title: 'Reset Password v1', to: 'pages-authentication-reset-password-v1', target: '_blank' },
                    { title: 'Reset Password v2', to: 'pages-authentication-reset-password-v2', target: '_blank' },
                ],
            },
        ],
    },
]
