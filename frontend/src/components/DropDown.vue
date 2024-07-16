<script setup lang="ts">
	import "./scss/DropDown.scss";
	import { ref, watch, Ref, onMounted, defineProps, defineEmits } from "vue";

	const props = defineProps(["options", "default", "tabindex"]);
	const emits = defineEmits(["inputOnMounted","input","inputIndex"]);

	const selectedIndex : Ref<number> = ref(0);
	const selected = ref(props.default || (props.options.length > 0 ? props.options[0] : null));

	watch(props, () => 
	{
		selected.value = props.options[selectedIndex.value];
	});

	const open : Ref<boolean> = ref(false);

	onMounted(() => 
	{
		emits("inputOnMounted", selected.value);
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
					selectedIndex = i;
					open = false;
					$emit('inputIndex', i as number);
					$emit('input', option as string);">
				{{ option }}
			</div>      
        </div>
    </div>
</template>


<style lang="scss" scoped src="./scss/DropDown.scss"></style>