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
        maxlength: Number,
        autofocus: Boolean,
    });
    const emits = defineEmits(["update:value"]);
  
    const textarea : Ref<HTMLTextAreaElement | null> = ref(null);
    const textareaText : Ref<string> = ref('');

    const autoResize = () =>  
    {
        const textareaValue = textarea.value;
        if (textareaValue) 
        {
            textareaValue.style.height = 'auto';
            textareaValue.style.height = `${textareaValue.scrollHeight}px`;
        }
    }

    watch(textareaText, (newValue) => 
    {
        textareaText.value = removeLineBreakFromString(newValue);
        emits("update:value", textareaText.value);
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
        :rows="props.rows"
        :placeholder="props.placeholder"
        :maxlength="props.maxlength"
        :autofocus="props.autofocus"
    ></textarea>
</template>

<style lang="scss" scoped src="./scss/AutoexpandTextarea.scss"></style>