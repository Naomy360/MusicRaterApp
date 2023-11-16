import React, { useState } from 'react';
import { StyleSheet, Text, View, TextInput, TouchableOpacity, Alert } from 'react-native';

const SignUp = ({ navigation }) => {
  // State variables for storing username, password, and confirmPassword
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  // Function to handle the registration process
  const handleRegister = async () => {
    // Check if passwords match
    if (password !== confirmPassword) {
      Alert.alert('Error', 'Passwords do not match.');
      return;
    }
    
    // Check if the username meets the minimum length requirement
    if (username.length < 4) {
      Alert.alert('Error', 'Username must be at least 4 characters long.');
      return;
    }
    
    // Check if the password meets the minimum length requirement
    if (password.length < 6) {
      Alert.alert('Error', 'Password must be at least 6 characters long.');
      return;
    }

    try {
      // Send a POST request to the server for signup
      const response = await fetch('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          action: 'signup', // Include the action attribute here
          username: username,
          password: password,
        }),
      });

      // Parse the response as JSON
      const json = await response.json();

      // Check if the registration was successful
      if (json.success) { 
        // Navigate to the Index page on success
        navigation.navigate('Index', { loggedInUsername: username });
      } else {
        // If registration is not successful, show an alert
        Alert.alert('Registration Failed', json.message || 'You could not be registered at this time.');
      }
    } catch (error) {
      // Handle errors that occur during the registration process
      Alert.alert('Error', `An error occurred: ${error.message}`);
    }
  };

  // Render the signup screen components
  return (
    <View style={styles.container}>
      <Text style={styles.header}>Sign Up</Text>
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
      {/* Input field for confirming the password */}
      <TextInput
        style={styles.input}
        placeholder="Confirm Password"
        secureTextEntry
        value={confirmPassword}
        onChangeText={setConfirmPassword}
      />
      {/* Button to initiate the registration process */}
      <TouchableOpacity style={styles.button} onPress={handleRegister}>
        <Text style={styles.buttonText}>Register</Text>
      </TouchableOpacity>
      {/* Text with a link to navigate to the Login screen */}
      <Text style={styles.signInText}>
        Already have an account? 
        <Text style={styles.signInButton} onPress={() => navigation.navigate('Login')}>
          Sign In
        </Text>
      </Text>
    </View>
  );
};

// Styles for the SignUp component
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
  signInText: {
    marginTop: 20,
    textAlign: 'center',
  },
  signInButton: {
    color: 'blue',
    paddingLeft: 5,
  },
});

// Export the SignUp component
export default SignUp;
