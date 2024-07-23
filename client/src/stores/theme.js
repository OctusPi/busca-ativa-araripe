import { ref } from 'vue'

const theme = ref(localStorage.getItem('theme_merenda'))

function setTheme(themeValue){
  localStorage.setItem('theme_merenda', themeValue)
  theme.value = themeValue
}

function clear(){
  localStorage.removeItem('theme_merenda')
}

export default {
  theme:theme.value,
  setTheme,
  clear
}