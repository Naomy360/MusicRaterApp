import React from 'react';
import { StatusBar } from 'expo-status-bar';
import { NavigationContainer } from '@react-navigation/native';
import { createNativeStackNavigator } from '@react-navigation/native-stack';

import Login from './Login';
import SignUp from './SignUp';
import Index from './Index';

// Create a native stack navigator
const Stack = createNativeStackNavigator();

function App() {
  return (
    <>
      {/* NavigationContainer is the root container for the entire navigation tree */}
      <NavigationContainer>
        {/* Stack.Navigator defines the stack-based navigation structure */}
        <Stack.Navigator initialRouteName="Login">
          {/* Stack.Screen represents a screen component in the navigator */}
          <Stack.Screen name="Login" component={Login} options={{ title: 'Login' }} />
          <Stack.Screen name="SignUp" component={SignUp} options={{ title: 'Sign Up' }} />
          <Stack.Screen name="Index" component={Index} options={{ title: 'FaveTune' }} />
        </Stack.Navigator>
        {/* StatusBar provides a status bar at the top of the app */}
        <StatusBar style="auto" />
      </NavigationContainer>
    </>
  );
}

export default App;
