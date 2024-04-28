import { ref, nextTick } from 'vue'

export const componentsShow = ref(true);

export const forceReload = async () => 
{
    componentsShow.value = false;

    await nextTick();
    
    componentsShow.value = true;
}