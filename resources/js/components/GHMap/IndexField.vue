<template>
    <span v-if="field.latitude && field.longitude">
        {{ field.latitude + ', ' + field.longitude }}
    </span>
    <span v-else>—</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
}
</script>
