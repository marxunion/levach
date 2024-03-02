<script setup lang="ts">
import "./scss/DropDown.scss";
import { ref, computed, onMounted, defineProps, defineEmits } from "vue";

const props = defineProps(["options", "value", "default", "tabindex"]);
const emits = defineEmits(["input"]);

const selected = computed(() => (props.default || (props.options.length > 0 ? props.options[0] : null)));
const open = ref(false);

onMounted(() => {
  emits("input", selected.value);
});

</script>

<template>
    <div class="customSelect" :tabindex="tabindex" @blur="open = false">
        <div class="customSelect__selected" :class="{ open: open }" @click="open = !open">
            {{ selected }}
			<span class="customSelect__selected__img"></span>
        </div>
        <div class="customSelect__items" :class="{ selectHide: !open }">
			<div 
				v-for="(option, i) of options" 
				:key="i"
				@click="
					selected = option;
					open = false;
					$emit('input', option as string);">
				{{ option }}
			</div>      
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/DropDown.scss"></style>