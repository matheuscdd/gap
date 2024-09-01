<template>
    {{ name }}
    <div :class="[$style.external, {[$style.focused]: focused}, {[$style.invalid]: empty || errors.length}]">
        <div :class="$style.intermediary" :for="id" @click="toggleOpen">
            <legend>{{ label }}</legend>
            <div :class="$style.internal">
                <iSvg 
                    :src="require(`@/assets/icons/${iconSelected || icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <input 
                    :id="id" 
                    :placeholder="text || placeholder" 
                    type="text"
                    @focusin="onFocus" 
                    @blur="outFocus" 
                    :class="{selected}"
                    :readonly="true"
                />
                <input 
                    type="hidden" 
                    :value="modelValue" 
                    @input="updateValue"
                />
            </div>
        </div>
        <div v-show="open" :class="$style['container-list-opts']">
            <ul :class="$style['list-opts']">
                <li
                    v-for="opt in opts"
                    :key="opt.value"
                    :data-value="opt.value"
                    :data-text="opt.text"
                    :data-icon="opt.icon"
                    @click="select"
                >
                <iSvg 
                    :src="require(`@/assets/icons/${opt.icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                    <span>{{ opt.text }}</span>
                </li>
            </ul>
        </div>
    </div>

</template>

<script>
import { getUUID, sleep } from "@/common/utils";
import styles from "@/global/bInput.module.css";

export default {
    data: () => ({
        id: getUUID(),
        value: "",
        open: false,
        selected: false,
        focused: false,
        empty: false,
        text: "",
        iconSelected: null
    }),
    props: [
        "opts", 
        "name",
        "label", 
        "icon",
        "placeholder",
        "errors",
        "modelValue", 
    ],
    computed: {
        $style() {
            return styles;
        },
    },  
    methods: {
        select(e) {
            const target = e.target.tagName !== "LI" ? e.target.closest("LI") : e.target;
            this.selected = true;
            this.open = false;
            this.empty = false;
            this.text =  target.dataset.text;
            this.$emit("update:modelValue", target.dataset.value);
            this.iconSelected = target.dataset.icon;
        },
        onFocus() {
            this.focused = true;
        },
        async outFocus() {
            await sleep(200);
            this.open = false;
            this.focused = false;
            this.empty = !this.selected;
        },
        toggleOpen() {
            this.open = !this.open;
            this.empty = !this.selected;
        },
        updateValue(e) {
            this.$emit("update:modelValue", e.target.value.trim());
        },
    },
};
</script>

<style module>
@import "@/global/bInput.module.css";
</style>