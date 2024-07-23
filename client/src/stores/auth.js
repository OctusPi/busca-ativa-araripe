import { ref } from 'vue'
import axios from 'axios'

const token = ref(localStorage.getItem('token_merenda'))
const user = ref(localStorage.getItem('user_merenda'))

function setToken(tokenValue){
    localStorage.setItem('token_merenda', tokenValue)
    token.value = tokenValue
}


function setUser(userValue){
  const str_value = JSON.stringify(userValue)
  localStorage.setItem('user_merenda', str_value)
  user.value = str_value
}

function getUser(){
  try {
    return JSON.parse(user.value);
  } catch (e) {
    console.log('Fail parse string to JSON')
    return null
  }
}

function clear(){
  localStorage.removeItem('token_merenda')
  localStorage.removeItem('user_merenda')
}

async function isLoggedIn(path){

  const {data} = await axios.get(import.meta.env.VITE_URL_API+path, {
    headers:{
      'Accept': 'application/json',
      'Authorization':'Bearer '+token.value
    }
  })

  return data;
  
}

export default {
  token: token.value,
  setToken,
  setUser,
  getUser,
  clear,
  isLoggedIn
}