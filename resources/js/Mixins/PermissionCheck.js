export default {
    methods: {
        hasAnyPermission(permissions = []) {
            if(!permissions.length) return true;

            return permissions.some(p => this.$page.props.shared.userPermissions.includes(p))
        }
    }
}
