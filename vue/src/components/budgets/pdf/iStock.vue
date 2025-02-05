<template>
    <div :class="{container: true, generating}">
        <div>{{ sequence }}</div>
        <div>{{ name }}</div>
        <div>{{ extra || '-' }}</div>
        <div>{{ type }}</div>
        <div>{{ quantity }}</div>
        <div>{{ weight }} kg</div>
    </div>
</template>
<script>
export default {
    props: [
        "sequence",
        "name",
        "quantity",
        "weight",
        "type",
        "extra",
        "generating",
    ],
};
</script>
<style scoped>
.container {
    display: grid;
    grid-auto-rows: 20px;
    grid-template-columns: 10% 40% 15% 15% 10% 10%;
    font-size: 14px;
}

.container div {
    margin-left: 10px;
}

.container div:not(:last-child) {
    border-right: 1px solid var(--gray-1);
}

@media (max-width: 1100px) {
    .container:not(.generating) {
        font-size: 10px;
    }

    .container:not(.generating) div {
        margin-left: 3px;
    }
}
</style>