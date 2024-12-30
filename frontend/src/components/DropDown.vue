<script setup lang="ts">
	import "./scss/DropDown.scss";
	import { ref, watch, Ref, onMounted, defineProps, defineEmits } from "vue";

	const props = defineProps(
    {
        options: 
        {
            type: Array<String>,
            default: [],
        },
        default:
        {
            type: String,
            default: '',
        }
    });

	type UpdateValuePayload = 
	{
		option: String;
		index: number;
	};

	const emits = defineEmits<{
		(e: 'start:value', payload: String): void;
		(e: 'update:value', payload: UpdateValuePayload): void;
	}>();

	const selectedIndex : Ref<number> = ref(0);
	const selected : Ref<String | null> = ref(props.default || (props.options.length > 0 ? props.options[0] : null));

	
	;
	watch(props, () => 
	{
		selected.value = props.options[selectedIndex.value];
	});

	const open : Ref<boolean> = ref(false);

	onMounted(() => 
	{
		if(selected.value)
		{
			emits("start:value", selected.value);
		}
	});
</script>

<template>
    <div class="customSelect" @blur="open = false">
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
					$emit('update:value', {option: option as string, index: i as number});">
				{{ option }}
			</div>      
        </div>
    </div>
</template>

<style lang="scss" scoped src="./scss/DropDown.scss"></style>