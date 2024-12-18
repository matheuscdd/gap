<template>
    <div class="card" :title="title || value">
        <iSvg
            :src="require(`@/assets/icons/${icon}.svg`)"
            width="16"
            height="16"
            fill="white"
        />
        <span>{{ value }}</span>
    </div>
</template>
<script>
export default {
    props: ["value", "icon", "title"],
};
</script>
<style scoped>
.card {
    display: flex;
    gap: 5px;
}

@media (max-width: 1100px) {
    .card {
        justify-content: center;
    }
}
</style>
