<template>
    <fieldset>
        <legend>{{ legend }}</legend>
        <label>{{ value }}</label>
    </fieldset>
</template>
<script>
export default {
    props: ["value", "legend"],
};
</script>
<style scoped>
fieldset {
    text-transform: uppercase;
}

fieldset {
    border: 2px solid var(--green-3);
    padding: 10px;
    border-radius: 5px;
}

legend {
    color: var(--green-3);
    font-size: 12px;
    padding: 0 5px;
}
</style>
