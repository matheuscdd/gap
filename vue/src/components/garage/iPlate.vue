<template>
    <div>
        <span>{{ plate }}</span>
    </div>
</template>

<script>
export default {
    props: [
        "plate"
    ],
};
</script>
<style scoped>
div {
    width: 140px;
    height: 40px;
    border: 1px solid var(--black-1);
    border-radius: 5px;
    overflow: hidden;
}

span {
    border-top: 10px solid var(--blue-3);
    display: flex;
    justify-content: center;
    font-weight: 500;
    font-size: 30px;
    line-height: 32px;
    font-family: "Khand", serif;
}
</style>