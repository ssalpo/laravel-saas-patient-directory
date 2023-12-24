<template>
    <FormSelect
        ref="selectLocations"
        v-bind="$attrs"
        searchable
        prefetch
        remote
        :remote-url="route('autocomplete.locations')"
        full
        @selected="$emit('selected', $event)"
        placeholder="Выберите локацию"
    />
</template>

<script>
import FormSelect from "./FormSelect.vue";

export default {
    name: "FormSelectLocations",
    components: {FormSelect},
    methods: {
        refreshData() {
            this.$refs.selectLocations.refreshData()
        }
    }
}
</script>
