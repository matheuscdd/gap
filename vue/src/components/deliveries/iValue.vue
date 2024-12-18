<template>
    <li class="container-external" :style="{borderColor: color}" :title="value">
        <div class="container-internal">
            <h6 :style="{fontSize: `${font}px`}">{{ formattedValue }}</h6>
            <small v-show="label">{{ label }}</small>
        </div>
        <iSvg 
            :src="require(`@/assets/icons/${icon}.svg`)"
            width="60" 
            height="60"
            :fill="color"
        />
    </li>
</template>
<script>
export default {
    props: [
        "value",
        "icon",
        "color",
        "font",
        "label",
    ],
    computed: {
        formattedValue() {
            const limit = 10;
            const value = String(this.value);
            return value.length > limit ? value.slice(0, limit) + "..." : value;
        }
    },
};
</script>
<style scoped>
.container-external {
    width: 200px;
    height: 100px;
    border-radius: var(--radius-2);
    color: #fff;
    background-color: rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
    border: 5px solid;
    user-select: none;
    backdrop-filter: blur(6px);
}

.container-internal {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    flex-direction: column;
    height: 100%;
    gap: 2px;
    margin-right: 20px;
}

small {
    font-size: 11px;
}

svg {
    position: absolute;
    top: 38px;
    transform: rotate(330deg);
    opacity: 0.5;
}
</style>