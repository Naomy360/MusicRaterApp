import React from 'react';
import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';


import Login from './Login';
import SignUp from './SignUp';
import Index from './Index';

const Stack = createNativeStackNavigator();

function App() {
  return (
    <>
      <NavigationContainer>
        <Stack.Navigator initialRouteName="Login">
          <Stack.Screen name="Login" component={Login} options={{ title: 'Login' }} />
          <Stack.Screen name="SignUp" component={SignUp} options={{ title: 'Sign Up' }} />
          <Stack.Screen name="Index" component={Index} options={{ title: 'FaveTune' }} />
        </Stack.Navigator>
        <StatusBar style="auto" />
      </NavigationContainer>

    

    </>
  );
}

export default App;























