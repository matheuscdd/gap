<template>
    <div :class="[$style.external, {[$style.invalid]: empty || errors.length}]">
        <label :class="$style.intermediary" :for="id">
            <legend>{{ label }}</legend>
            <div :class="$style.internal">
                <iSvg 
                    :src="require(`@/assets/icons/${icon}.svg`)"
                    width="16" 
                    height="16"
                    fill="currentColor"
                />
                <input 
                    :disabled="!!readonly"
                    :id="id" 
                    :placeholder="placeholder" 
                    :type="kind" 
                    :value="modelValue"
                    :maxlength="maxlength"
                    @blur="outFocus" 
                    @focusin="updateValue"
                    @input="updateValue"
                />
                <button 
                    type="button"
                    v-show="type === 'password'" 
                    @click="showPassword = !showPassword"
                >
                    <iSvg 
                        :src="require(`@/assets/icons/${showPassword ? 'eye-slash-solid' : 'eye-solid'}.svg`)"
                        width="16" 
                        height="16"
                        fill="currentColor"
                    />
                </button>
            </div>
        </label>
        
        <ul v-show="errors.length" :class="$style.errors">
            <li v-for="el in errors" :key="el + getUUID()">
                <legend>{{ el }}</legend>
            </li>
        </ul>
    </div>
</template>

<script>
import { getUUID } from "@/common/utils";
import styles from "@/global/bInput.module.css";

export default {
    data: () => ({
        id: getUUID(),
        empty: false,
        showPassword: false,
    }),
    props: [
        "placeholder", 
        "icon", 
        "label", 
        "name", 
        "modelValue", 
        "type",
        "errors",
        "maxlength",
        "readonly"
    ],
    watch: {
        modelValue(value) {
            this.empty = !String(value).length;
        }
    },
    computed: {
        $style() {
            return styles;
        },
 
        kind() {
            if (this.type === "password" ) {
                return this.showPassword ? "text" : "password";
            } else if (this.type === "number") {
                return "text";
            }
            return this.type;
        }

    },
    methods: {   
        outFocus() {
            this.empty = !String(this.modelValue).length;
            this.$emit("validate", this.name);
        },
        updateValue(e) {
            let val = e.target.value;
            if (this.type === "number") {
                val = e.target.value.replace(/\D/g, "");
                if (e.target.value.length) val = Number(val);
            }
            this.$emit("update:modelValue", val);
        },
        getUUID
    }
};

</script>

<style module>
@import "@/global/bInput.module.css";
</style>