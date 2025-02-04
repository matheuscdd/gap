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
                    @blur="outFocus"
                    ref="input"
                />
            </div>
        </div>
        <datalist :id="name">
            <option
                v-for="opt in opts"
                :key="opt.id"
                :value="opt.name"
            >
                {{ opt.name }}
            </option>
        </datalist>
        <ul v-show="errors.length" :class="$style.errors">
            <li v-for="el in errors" :key="el">
                <legend>{{ el }}</legend>
            </li>
        </ul>
    </div>
</template>

<script>
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
        "validator",
        "modelValue",
        "edit"
    ],
    computed: {
        $style() {
            return styles;
        },
    },
    watch: {
        modelValue() {
            const id = this.modelValue;
            if (!id || !this.edit) return;
            const name = this.opts.find(opt => opt.id === id).name;
            this.$refs.input.value = name;
        }
    },
    methods: {
        updateValue(e) {
            const opt = this.opts.find(opt => opt.name === e.target.value)?.id || "";
            this.$emit("update:modelValue", opt);
        },
        outFocus() {
            return this.$emit("validate", this.name);
        }
    }
};
</script>

<style module>
@import "@/global/bInput.module.css";
</style>