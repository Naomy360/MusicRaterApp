import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, Modal, TextInput, ScrollView, Alert } from 'react-native';
import axios from 'axios';


const Index = ({ route }) => {
  const [songs, setSongs] = useState([]);
  const [addSongVisible, setAddSongVisible] = useState(false);
  const [updateSongVisible, setUpdateSongVisible] = useState(false);
  const [newSong, setNewSong] = useState({ artist: '', song: '', rating: '' });
  const [updateSong, setUpdateSong] = useState({ id: '', artist: '', song: '', rating: '' });
  const [loggedInUsername, setLoggedInUsername] = useState('');

  const fetchSongs = async () => {
    try {
      const response = await axios.get('http://172.21.134.19/MusicRaterApp/Public/Index.php');
      setSongs(response.data);
    } catch (error) {
      console.error('Error fetching songs:', error);
    }
  };

  useEffect(() => {
    fetchSongs();
    if (route.params?.loggedInUsername) {
      setLoggedInUsername(route.params.loggedInUsername);
    }
  }, [route.params?.loggedInUsername]);

  const addNewSongRating = async () => {
    try {
      const response = await axios.post('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        action: 'createRating',
        username: loggedInUsername,
        artist: newSong.artist,
        song: newSong.song,
        rating: newSong.rating
      });

      if (response.data && response.data.success) {
        console.log('Rating added successfully:', response.data.message);
        setNewSong({ artist: '', song: '', rating: '' });
        setAddSongVisible(false);
        await fetchSongs();
      } else {
        console.error('Failed to add rating:', response.data.message);
      }
    } catch (error) {
      console.error('Error adding new song rating:', error.response ? error.response.data : error);
    }
  };

  const updateSongRating = async () => {
    try {
      const response = await axios.put('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        id: updateSong.id,
        artist: updateSong.artist,
        song: updateSong.song,
        rating: updateSong.rating,
        username: loggedInUsername
      });

      if (response.data && response.data.success) {
        console.log('Rating updated successfully:', response.data.message);
        setUpdateSong({ id: '', artist: '', song: '', rating: '' });
        setUpdateSongVisible(false);
        await fetchSongs();
      } else {
        Alert.alert("Update Failed", response.data.message);
      }
    } catch (error) {
      console.error('Error updating song rating:', error.response ? error.response.data : error);
    }
  };

  const deleteSongRating = async (id) => {
    try {
      const response = await axios.delete('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        data: { id }
      });

      if (response.data && response.data.success) {
        console.log('Rating deleted successfully:', response.data.message);
        await fetchSongs();
      } else {
        console.error('Failed to delete rating:', response.data.message);
      }
    } catch (error) {
      console.error('Error deleting song rating:', error.response ? error.response.data : error);
    }
  };

  const renderAddSongForm = () => (
    <Modal
      animationType="slide"
      transparent={true}
      visible={addSongVisible}
      onRequestClose={() => setAddSongVisible(false)}
    >
      <View style={styles.modalContainer}>
        <View style={styles.modalContent}>
          <ScrollView>
            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Artist"
                value={newSong.artist}
                onChangeText={(text) => setNewSong({ ...newSong, artist: text })}
              />
              <TextInput
                style={styles.input}
                placeholder="Song"
                value={newSong.song}
                onChangeText={(text) => setNewSong({ ...newSong, song: text })}
              />
              <TextInput
                style={styles.input}
                placeholder="Rating"
                keyboardType="numeric"
                value={newSong.rating}
                onChangeText={(text) => setNewSong({ ...newSong, rating: text })}
              />
              <TouchableOpacity style={styles.submitButton} onPress={addNewSongRating}>
                <Text style={styles.buttonText}>Submit</Text>
              </TouchableOpacity>
            </View>
          </ScrollView>
        </View>
      </View>
    </Modal>
  );

  const renderUpdateSongForm = () => (
    <Modal
      animationType="slide"
      transparent={true}
      visible={updateSongVisible}
      onRequestClose={() => setUpdateSongVisible(false)}
    >
      <View style={styles.modalContainer}>
        <View style={styles.modalContent}>
          <ScrollView>
            <View style={styles.inputContainer}>
              <TextInput
                style={styles.input}
                placeholder="Artist"
                value={updateSong.artist}
                onChangeText={(text) => setUpdateSong({ ...updateSong, artist: text })}
              />
              <TextInput
                style={styles.input}
                placeholder="Song"
                value={updateSong.song}
                onChangeText={(text) => setUpdateSong({ ...updateSong, song: text })}
              />
              <TextInput
                style={styles.input}
                placeholder="Rating"
                keyboardType="numeric"
                value={updateSong.rating}
                onChangeText={(text) => setUpdateSong({ ...updateSong, rating: text })}
              />
              <TouchableOpacity style={styles.submitButton} onPress={updateSongRating}>
                <Text style={styles.buttonText}>Update</Text>
              </TouchableOpacity>
            </View>
          </ScrollView>
        </View>
      </View>
    </Modal>
  );

  const renderSongItem = ({ item }) => (
    <View style={styles.songItem}>
      <Text style={styles.songTitle}>{item.song} by {item.artist}</Text>
      <Text style={styles.rating}>Rating: {item.rating}</Text>
      {item.username === loggedInUsername && (
        <>
          <TouchableOpacity 
            style={styles.updateButton} 
            onPress={() => { 
              setUpdateSong({ 
                id: item.id, 
                artist: item.artist, 
                song: item.song, 
                rating: item.rating.toString() 
              }); 
              setUpdateSongVisible(true); 
            }}
          >
            <Text style={styles.buttonText}>Update</Text>
          </TouchableOpacity>
          <TouchableOpacity 
            style={styles.deleteButton} 
            onPress={() => deleteSongRating(item.id)}
          >
            <Text style={styles.buttonText}>Delete</Text>
          </TouchableOpacity>
        </>
      )}
    </View>
  );

  return (
    <View style={styles.container}>
      {renderAddSongForm()}
      {renderUpdateSongForm()}
      <FlatList
        data={songs}
        renderItem={renderSongItem}
        keyExtractor={(item) => item.id.toString()}
      />
      <TouchableOpacity style={styles.addButton} onPress={() => setAddSongVisible(true)}>
        <Text style={styles.buttonText}>Add New Rating</Text>
      </TouchableOpacity>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#E8F4F8', 
  },
  songItem: {
    padding: 20,
    borderBottomWidth: 1,
    borderBottomColor: '#B0BEC5', 
    backgroundColor: '#FFFFFF', 
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  songTitle: {
    fontSize: 18,
    color: '#455A64', 
  },
  rating: {
    fontSize: 16,
    color: '#607D8B', 
  },
  deleteButton: {
    backgroundColor: '#E57373',
    padding: 5,
    borderRadius: 5,
    alignItems: 'center',
  },
  updateButton: {
    backgroundColor: '#FFD54F', 
    padding: 5,
    borderRadius: 5,
    alignItems: 'center',
    marginRight: 10,
  },
  buttonText: {
    color: '#FFFFFF', 
  },
  addButton: {
    backgroundColor: '#4FC3F7', 
    padding: 10,
    margin: 10,
    borderRadius: 5,
    alignItems: 'center',
  },
  input: {
    backgroundColor: '#CFD8DC', 
    color:'#455A64', // 
    height: 40,
    marginBottom: 12,
    borderWidth: 1,
    borderColor: '#B0BEC5', 
    padding: 10,
    borderRadius: 5,
    width: '100%',
  },
  modalContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: 'rgba(0, 0, 0, 0.5)', 
  },
  modalContent: {
    backgroundColor: '#FFFFFF', 
    padding: 35,
    width: '80%',
    borderRadius: 10,
  },
  submitButton: {
    backgroundColor: '#4CAF50', 
    padding: 10,
    borderRadius: 5,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: {
    color: '#FFFFFF', 
    fontWeight: 'bold', 
  },
});


export default Index;