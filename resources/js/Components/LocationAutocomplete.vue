<template>
    <div>
      <input v-model="location" type="text" placeholder="Escribe la ubicación" @input="autocompleteLocation">
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
  