<template>
    <div :class="[$style.external, {[$style.focused]: focused}, {[$style.invalid]: empty || errors.length}]">
        <div :class="$style.intermediary" @click="toggleOpen">
            <legend>{{ label }}</legend>
            <div :class="$style.internal">
                <iSvg 
                    :src="require(`@/assets/icons/${iconSelected || icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <input 
                    :list="name"
                    @input="updateValue"
                />
            </div>
        </div>
        <datalist :id="name">
            <option
                v-for="opt in opts"
                :key="opt.value"
                :value="opt.value"
            >
                {{ opt.text }}
            </option>
        </datalist>
    </div>
</template>

<script>
import { getUUID, sleep } from "@/common/utils";
import styles from "@/global/bInput.module.css";

export default {
    data: () => ({
        
    }),
    props: [
        "opts", 
        "name",
        "label", 
        "icon",
        "errors",
        "modelValue", 
        "validator"
    ],
    computed: {
        $style() {
            return styles;
        },
    },  
    methods: {
        updateValue(e) {
            const opt = this.opts.find(opt => opt.text === e.target.value)?.id || "";
            this.$emit("update:modelValue", opt);
        },
    }
};
</script>

<style module>
@import "@/global/bInput.module.css";
</style>