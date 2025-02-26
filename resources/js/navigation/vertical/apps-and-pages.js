export default [
    { heading: 'Posts' },
    {

        title: 'Posts',
        icon: { icon: 'tabler-article' },
        children: [
            { title: 'List', to: 'apps-ecommerce-product-list' },
            { title: 'Add', to: 'apps-ecommerce-product-add' },

        ],
    },
    {
        title: 'Category',
        to: 'apps-ecommerce-product-category-list',
        icon: { icon: 'tabler-category' }
    },
    {
        title: 'Comments',
        to: '',
        icon: { icon: 'tabler-message-circle' }
    },

    {
        title: 'Tags',
        to: '',
        icon: { icon: 'tabler-tag' }
    },

    {
        title: 'Order',
        icon: { icon: 'tabler-shopping-cart' },
        children: [
            { title: 'List', to: 'apps-ecommerce-order-list' },
            { title: 'Details', to: { name: 'apps-ecommerce-order-details-id', params: { id: '9042' } } },
        ],
    },
    {
        title: 'Email',
        icon: { icon: 'tabler-mail' },
        to: 'apps-email',
    },
    {
        title: 'Chat',
        icon: { icon: 'tabler-message-circle' },
        to: 'apps-chat',
    },

    {
        title: 'User',
        icon: { icon: 'tabler-users' },
        children: [
            { title: 'List', to: 'apps-user-list' },
            { title: 'View', to: { name: 'apps-user-view-id', params: { id: 21 } } },
        ],
    },
    { heading: 'Forms & Tables' },
    {
        title: 'Form Elements',
        icon: { icon: 'tabler-checkbox' },
        children: [
            { title: 'Autocomplete', to: 'forms-autocomplete' },
            { title: 'Checkbox', to: 'forms-checkbox' },
            { title: 'Combobox', to: 'forms-combobox' },
            { title: 'Date Time Picker', to: 'forms-date-time-picker' },
            { title: 'Editors', to: 'forms-editors' },
            { title: 'File Input', to: 'forms-file-input' },
            { title: 'Radio', to: 'forms-radio' },
            { title: 'Custom Input', to: 'forms-custom-input' },
            { title: 'Range Slider', to: 'forms-range-slider' },
            { title: 'Rating', to: 'forms-rating' },
            { title: 'Select', to: 'forms-select' },
            { title: 'Slider', to: 'forms-slider' },
            { title: 'Switch', to: 'forms-switch' },
            { title: 'Textarea', to: 'forms-textarea' },
            { title: 'Textfield', to: 'forms-textfield' },
        ],
    },
    {
        title: 'Form Layouts',
        icon: { icon: 'tabler-layout' },
        to: 'forms-form-layouts',
    },
    {
        title: 'Tables',
        icon: { icon: 'tabler-table' },
        children: [
            { title: 'Simple Table', to: 'tables-simple-table' },
            { title: 'Data Table', to: 'tables-data-table' },
        ],
    },

    {
        title: 'Roles & Permissions',
        icon: { icon: 'tabler-settings' },
        children: [
            { title: 'Roles', to: 'apps-roles' },
            { title: 'Permissions', to: 'apps-permissions' },
        ],
    },
]
