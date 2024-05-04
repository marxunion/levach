import { ref, nextTick } from 'vue'

export const componentsShow = ref(true);

export const forceReload = async () => 
{
    console.log('test1');
    componentsShow.value = false;

    await nextTick();
    
    componentsShow.value = true;
}