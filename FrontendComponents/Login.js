import React, { useState } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, Alert } from 'react-native';

const Login = ({ navigation }) => {
  // State variables for storing username and password
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  // Function to handle the login process
  const handleLogin = async () => {
    // Perform basic validation
    if (username.length === 0 || password.length === 0) {
      Alert.alert('Error', 'Please enter your username and password.');
      return;
    }
    
    try {
      // Send a POST request to the server for login
      const response = await fetch('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          action: 'login', // Specify the action for the backend to handle
          username: username,
          password: password,
        }),
      });

      // Parse the response as JSON
      const json = await response.json();

      // Check if the login was successful
      if (json.success) {
        // If login is successful, navigate to the Index (home) screen
        navigation.navigate('Index', { loggedInUsername: username });
      } else {
        // If login is not successful, show an alert
        Alert.alert('Login Failed', json.message);
      }
    } catch (error) {
      // Handle errors that occur during the login process
      Alert.alert('Error', `An error occurred: ${error.message}`);
    }
  };

  // Render the login screen components
  return (
    <View style={styles.container}>
      <Text style={styles.header}>Login Page</Text>
      {/* Input field for the username */}
      <TextInput
        style={styles.input}
        placeholder="Username"
        value={username}
        onChangeText={setUsername}
      />
      {/* Input field for the password */}
      <TextInput
        style={styles.input}
        placeholder="Password"
        secureTextEntry
        value={password}
        onChangeText={setPassword}
      />
      {/* Button to initiate the login process */}
      <TouchableOpacity style={styles.button} onPress={handleLogin}>
        <Text style={styles.buttonText}>Login</Text>
      </TouchableOpacity>
      {/* Link to navigate to the SignUp screen */}
      <TouchableOpacity onPress={() => navigation.navigate('SignUp')}>
        <Text style={styles.registerText}>Don't have an account? Sign Up</Text>
      </TouchableOpacity>
    </View>
  );
};

// Styles for the Login component
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#fff',
    justifyContent: 'center',
    padding: 20,
  },
  header: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 30,
    textAlign: 'center',
  },
  input: {
    height: 40,
    marginBottom: 20,
    borderWidth: 1,
    borderColor: '#ccc',
    paddingHorizontal: 10,
  },
  button: {
    backgroundColor: '#0066cc',
    padding: 10,
  },
  buttonText: {
    color: '#ffffff',
    textAlign: 'center',
  },
  registerText: {
    marginTop: 20,
    color: 'blue',
    textAlign: 'center',
  },
});

// Export the Login component
export default Login;
