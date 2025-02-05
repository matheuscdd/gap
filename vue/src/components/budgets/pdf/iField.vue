<template>
    <li>
        <h6>{{ label }}</h6>
        <legend 
            :class="{small: isSmall, large: !isSmall, generating }"
        >
        {{ value }}
        </legend>
    </li>
</template>
<script>
export default {
    props: [
        "label",
        "value",
        "generating",
    ],
    computed: {
        isSmall() {
            return String(this.value).length > 100;
        }
    }
};
</script>
<style scoped>
li {
    list-style: none;
    border: 1px solid var(--gray-1);
    padding: 5px 10px;
    text-transform: uppercase;
}

h6 {
    font-size: 9px;
    margin-bottom: 3px;
}

.large {
    font-size: 16px;
}

.small {
    font-size: 14.5px;
}

@media (max-width: 1455px) {
    .large:not(.generating) {
        font-size: 14.5px;
    }

    .small:not(.generating) {
        font-size: 12px;
    }
}

@media (max-width: 1100px) {
    .large:not(.generating) {
        font-size: 12px;
    }

    .small:not(.generating) {
        font-size: 10px;
    }
}
</style>