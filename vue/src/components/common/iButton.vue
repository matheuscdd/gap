<template>
    <button 
        type="button" 
        :style="{'background-color': background}"
        @click="$emit('click')"
    >
        {{ text }}
    </button>
</template>

<script>
export default {
    props: [
        "text",
        "background"
    ]
};
</script>
<style scoped>
button {
    font-size: 15px;
    border-radius: 20px;
    cursor: pointer;
    padding: 8px 15px;
    border: transparent;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

button:hover {
    transition: 0.3s;
    opacity: 0.8;
}
</style>