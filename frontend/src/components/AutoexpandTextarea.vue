<script setup lang="ts">
    import { ref, watch , Ref, defineProps, defineEmits, onMounted } from 'vue';

    import { removeLineBreakFromString } from '../ts/helpers/StringHelper';

    const props = defineProps(
    {
        value: 
        {
            type: String,
            default: '',
        },
        placeholder:
        {
            type: String,
            default: '',
        },
        rows: 
        {
            type: Number,
            default: 1,
        },
        maxlength: 
        {
            type: Number,
            default: Infinity
        },
        autofocus: 
        {
            type: Boolean,
            default: false
        }
    });

    const emits = defineEmits<{
		(e: 'update', payload: string): void;
	}>();
  
    const textarea : Ref<HTMLTextAreaElement | null> = ref(null);
    const textareaText : Ref<string> = ref('');

    const autoResize = () =>  
    {
        const textareaValue = textarea.value;
        
        if (textareaValue) 
        {
            textareaValue.value = removeLineBreakFromString(textareaText.value);
            textareaValue.style.height = 'auto';
            textareaValue.style.height = `${textareaValue.scrollHeight}px`;
        }
    }

    watch(textareaText, () => 
    {
        textareaText.value = removeLineBreakFromString(textareaText.value);
        emits("update", textareaText.value);
        autoResize();
    });

    onMounted(() => 
    {
        textareaText.value = removeLineBreakFromString(props.value);
        autoResize();
    });
</script>

<template>
    <textarea
        v-model="textareaText"
        ref="textarea"
        class="autoexpandTextarea"
        :placeholder="props.placeholder"
        :maxlength="props.maxlength"
        :autofocus="props.autofocus"
        :rows="props.rows"
    ></textarea>
</template>

<style lang="scss" scoped src="./scss/AutoexpandTextarea.scss"></style>