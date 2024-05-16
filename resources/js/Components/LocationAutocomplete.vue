<template>
  <div class="relative z-0 w-full mb-5">
      <input v-model="location" type="text" placeholder="Escribe la ubicación" @input="autocompleteLocation" class="pt-3 pb-2 block w-full px-0 mt-0 bg-transparent border-0 border-b-2 appearance-none focus:outline-none focus:ring-0 focus:border-black border-gray-200" required>
      <div v-if="suggestions.length">
        <ul>
          <li v-for="suggestion in suggestions" :key="suggestion.place_id" @click="selectSuggestion(suggestion)">{{ suggestion.description }}</li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    name: 'LocationAutocomplete',
    props: ['onSelect'],
    setup(props) {
      const location = ref('');
      const suggestions = ref([]);
  
      const autocompleteLocation = async () => {
        if (!location.value) {
          suggestions.value = [];
          return;
        }
        const url = `https://maps.googleapis.com/maps/api/place/autocomplete/json?input=${location.value}&types=(cities)&key=AIzaSyBbfu-vRu7k7naT-Fh_457upAICHgZW1UI`;
        try {
          const response = await fetch(url);
          const data = await response.json();
          suggestions.value = data.predictions;
        } catch (error) {
          console.error('Error fetching location predictions:', error);
        }
      };
  
      const selectSuggestion = (suggestion) => {
        location.value = suggestion.description;
        suggestions.value = [];
        if (props.onSelect) {
          props.onSelect(suggestion);
        }
      };
  
      return {
        location,
        suggestions,
        autocompleteLocation,
        selectSuggestion
      };
    }
  };
  </script>
  