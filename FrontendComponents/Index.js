import React, { useState, useEffect } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, TextInput, Alert } from 'react-native';
import Icon from 'react-native-vector-icons/FontAwesome';
import axios from 'axios';
import { useNavigation } from '@react-navigation/native';
const Index = ({ route }) => {
  const [songs, setSongs] = useState([]);
  const [currentView, setCurrentView] = useState('list');
  const [selectedSong, setSelectedSong] = useState(null);
  const [loggedInUsername, setLoggedInUsername] = useState('');
  const [newSong, setNewSong] = useState({ artist: '', song: '', rating: '' });
  const [updateSong, setUpdateSong] = useState({ id: '', artist: '', song: '', rating: '' });
  const [ratingThreshold, setRatingThreshold] = useState('');
  const navigation = useNavigation();

  const handleExit = () => {
    navigation.navigate('Login');
  };

  useEffect(() => {
    fetchSongs();
    if (route.params?.loggedInUsername) {
        setLoggedInUsername(route.params.loggedInUsername);
    }
}, [route.params?.loggedInUsername]);


  const fetchSongs = async () => {
    try {
      const response = await axios.get('http://172.21.134.19/MusicRaterApp/Public/Index.php');
      setSongs(response.data);
    } catch (error) {
      console.error('Error fetching songs:', error);
    }
  };

  const isValidRating = (rating) => {
    const num = parseInt(rating, 10);
    return num >= 1 && num <= 5;
  };

  const handleAddSong = async (songData) => {
    if (!isValidRating(songData.rating)) {
      Alert.alert('Invalid Rating', 'Please enter a rating between 1 and 5.');
      return;
    }
    try {
      const response = await axios.post('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        action: 'createRating',
        username: loggedInUsername,
        artist: songData.artist,
        song: songData.song,
        rating: songData.rating
      });
      if (response.data.success) {
        console.log('Response:', response.data.message);
        setNewSong({ artist: '', song: '', rating: '' });
        setCurrentView('list');
        await fetchSongs();
      } else {
        Alert.alert("Rating Error", response.data.message);
      }
    } catch (error) {
      console.error('Error:', error.response ? error.response.data : error);
      Alert.alert("Error", "An error occurred while processing your request.");
    }
  };

  const handleUpdateSong = async (songData) => {
    if (!isValidRating(songData.rating)) {
      Alert.alert('Invalid Rating', 'Please enter a rating between 1 and 5.');
      return;
    }
    try {
      const response = await axios.put('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        id: songData.id,
        artist: songData.artist,
        song: songData.song,
        rating: songData.rating,
        username: loggedInUsername
      });
      if (response.data && response.data.success) {
        console.log('Rating updated successfully:', response.data.message);
        setUpdateSong({ id: '', artist: '', song: '', rating: '' });
        setCurrentView('list');
        await fetchSongs();
      } else {
        Alert.alert("Update Failed", response.data.message);
      }
    } catch (error) {
      console.error('Error updating song rating:', error.response ? error.response.data : error);
    }
  };

  const handleDeleteSong = async () => {
    try {
      const response = await axios.delete('http://172.21.134.19/MusicRaterApp/Public/Index.php', {
        data: { id: selectedSong.id }
      });
      if (response.data && response.data.success) {
        console.log('Rating deleted successfully:', response.data.message);
        setSelectedSong(null);
        setCurrentView('list');
        await fetchSongs();
      } else {
        console.error('Failed to delete rating:', response.data.message);
      }
    } catch (error) {
      console.error('Error deleting song rating:', error.response ? error.response.data : error);
    }
  };

  const FilteredSongList = () => {
    const filteredSongs = songs.filter(song => song.rating >= parseInt(ratingThreshold, 10) || ratingThreshold === '');

    return (
      <FlatList
        data={filteredSongs}
        renderItem={({ item }) => (
          <TouchableOpacity
            style={styles.songItem}
            onPress={() => {
              setSelectedSong(item);
              setCurrentView('details');
            }}
          >
            <Text style={styles.songTitle}>{item.song} by {item.artist}</Text>
            <View style={styles.rating}>
              {Array.from({ length: 5 }, (_, index) => (
                <Icon
                  key={index}
                  name="star"
                  size={15}
                  color={index < item.rating ? '#FFD700' : '#CCC'}
                />
              ))}
            </View>
            {item.username === loggedInUsername && (
              <View style={styles.buttonGroup}>
                <TouchableOpacity
                  style={styles.updateButton}
                  onPress={() => {
                    setUpdateSong({
                      id: item.id,
                      artist: item.artist,
                      song: item.song,
                      rating: item.rating.toString()
                    });
                    setCurrentView('update');
                  }}
                >
                  <Icon name="edit" size={20} color="#FFFFFF" />
                </TouchableOpacity>
                <TouchableOpacity
                  style={styles.deleteButton}
                  onPress={() => {
                    setSelectedSong(item);
                    setCurrentView('delete');
                  }}
                >
                  <Icon name="trash" size={20} color="#FFFFFF" />
                </TouchableOpacity>
              </View>
            )}
          </TouchableOpacity>
        )}
        keyExtractor={(item) => item.id.toString()}
      />
    );
  };
  
  const AddSongForm = () => {
    const [localSong, setLocalSong] = useState({ ...newSong });
  
    const handleSubmit = async () => {
      await handleAddSong(localSong); // Directly pass localSong here
    };
  
    return (
      <View style={styles.formContainer}>
        <TextInput
          style={styles.input}
          placeholder="Artist"
          value={localSong.artist}
          onChangeText={(text) => setLocalSong({ ...localSong, artist: text })}
        />
        <TextInput
          style={styles.input}
          placeholder="Song"
          value={localSong.song}
          onChangeText={(text) => setLocalSong({ ...localSong, song: text })}
        />
        <TextInput
          style={styles.input}
          placeholder="Rating (1-5)"
          keyboardType="numeric"
          value={localSong.rating}
          onChangeText={(text) => setLocalSong({ ...localSong, rating: text })}
        />
        <TouchableOpacity style={styles.submitButton} onPress={handleSubmit}>
          <Text style={styles.buttonText}>Submit</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.cancelButton} onPress={() => setCurrentView('list')}>
          <Text style={styles.buttonText}>Cancel</Text>
        </TouchableOpacity>
      </View>
    );
  };
  
  
  const UpdateSongForm = () => {
    const [localUpdateSong, setLocalUpdateSong] = useState({ ...updateSong });
  
    const handleUpdateSubmit = async () => {
      setUpdateSong(localUpdateSong);
      await handleUpdateSong(localUpdateSong); // Pass localUpdateSong
    };
  
    useEffect(() => {
      setLocalUpdateSong({ ...updateSong });
    }, [updateSong]);
  
    return (
      <View style={styles.formContainer}>
        <TextInput
          style={styles.input}
          placeholder="Artist"
          value={localUpdateSong.artist}
          onChangeText={(text) => setLocalUpdateSong({ ...localUpdateSong, artist: text })}
        />
        <TextInput
          style={styles.input}
          placeholder="Song"
          value={localUpdateSong.song}
          onChangeText={(text) => setLocalUpdateSong({ ...localUpdateSong, song: text })}
        />
        <TextInput
          style={styles.input}
          placeholder="Rating"
          keyboardType="numeric"
          value={localUpdateSong.rating}
          onChangeText={(text) => setLocalUpdateSong({ ...localUpdateSong, rating: text })}
        />
        <TouchableOpacity style={styles.submitButton} onPress={handleUpdateSubmit}>
          <Text style={styles.buttonText}>Update</Text>
        </TouchableOpacity>
        <TouchableOpacity style={styles.cancelButton} onPress={() => setCurrentView('list')}>
          <Text style={styles.buttonText}>Cancel</Text>
        </TouchableOpacity>
      </View>
    );
  };
  


  const DeleteConfirmation = () => (
    <View style={styles.deleteContainer}>
      <Text style={styles.confirmationText}>Are you sure you want to remove this song?</Text>
      <TouchableOpacity style={styles.submitButton} onPress={handleDeleteSong}>
        <Text style={styles.buttonText}>Remove</Text>
      </TouchableOpacity>
      <TouchableOpacity style={styles.cancelButton} onPress={() => setCurrentView('list')}>
        <Text style={styles.buttonText}>Cancel</Text>
      </TouchableOpacity>
    </View>
  );

  const SongDetails = () => {
    // Ensure selectedSong is not null
    if (!selectedSong) return null;
  
    return (
      <View style={styles.songDetailContainer}>
        <Text style={styles.songDetailTitle}>{selectedSong.song} by {selectedSong.artist}</Text>
        <Text style={styles.songDetailText}>Rating: {selectedSong.rating}</Text>
        {/* Add any other details you want to display here */}
        
        {/* Button to go back to the list */}
        <TouchableOpacity style={styles.backButton} onPress={() => setCurrentView('list')}>
          <Text style={styles.buttonText}>Back to List</Text>
        </TouchableOpacity>
      </View>
    );
  };
 

  const renderContent = () => {
    switch (currentView) {
      case 'list':
        return (
          <>
            <View style={styles.formContainer}>
              <TextInput
                style={styles.input}
                placeholder="Search by minimum rating (1-5)"
                keyboardType="numeric"
                value={ratingThreshold}
                onChangeText={setRatingThreshold}
              />
            </View>
            <FilteredSongList />
          </>
        );
      case 'add':
        return <AddSongForm />;
      case 'update':
        return <UpdateSongForm />;
      case 'delete':
        return <DeleteConfirmation />;
      case 'details':
        return <SongDetails />;
      default:
        return <SongList />;
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.usernameDisplay}>Username: {loggedInUsername}</Text>
      {renderContent()}
      {currentView === 'list' && (
        <TouchableOpacity style={styles.addButton} onPress={() => setCurrentView('add')}>
          <Text style={styles.buttonText}>Add New Rating</Text>
        </TouchableOpacity>
      )}
       <TouchableOpacity style={styles.exitButton} onPress={handleExit}>
        <Text style={styles.buttonText}>Exit</Text>
      </TouchableOpacity>
    </View>
  );
 
};

  
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#E8F4F8',
  },
  formContainer: {
    padding: 20,
    backgroundColor: '#FFFFFF',
    borderRadius: 10,
    marginHorizontal: 10,
    marginTop: 20,
  },
  deleteContainer: {
    padding: 20,
    alignItems: 'center',
    justifyContent: 'center',
    flex: 1,
  },
  confirmationText: {
    fontSize: 18,
    marginBottom: 20,
    color: '#333',
  },
  input: {
    backgroundColor: '#FFF',
    borderColor: '#DDD',
    borderWidth: 1,
    padding: 10,
    borderRadius: 5,
    marginBottom: 15,
    fontSize: 16,
  },
  submitButton: {
    backgroundColor: '#5cb85c',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    marginBottom: 10,
  },
  songDetailContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
    backgroundColor: '#E8F4F8',
  },
  songDetailTitle: {
      fontSize: 24,
      fontWeight: 'bold',
      color: '#333',
      marginBottom: 10,
    },
  songDetailText: {
      fontSize: 18,
      color: '#555',
      marginBottom: 5,
    },
  backButton: {
      marginTop: 20,
      backgroundColor: '#5bc0de',
      paddingVertical: 10,
      paddingHorizontal: 20,
      borderRadius: 5,
      alignItems: 'center',
    },
  buttonText: {
      color: '#FFFFFF',
      fontSize: 18,
      fontWeight: '600',
    },


  cancelButton: {
    backgroundColor: '#d9534f',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
  },
  addButton: {
    backgroundColor: '#5bc0de',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    marginHorizontal: 10,
    marginBottom: 10,
  },
  buttonText: {
    color: '#FFFFFF',
    fontSize: 18,
    fontWeight: '600',
  },
  buttonGroup: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginTop: 10,
  },
  updateButton: {
    backgroundColor: '#f0ad4e',
    padding: 10,
    borderRadius: 5,
    alignItems: 'center',
    marginRight: 10,
  },
  deleteButton: {
    backgroundColor: '#d9534f',
    padding: 10,
    borderRadius: 5,
    alignItems: 'center',
  },
  songItem: {
    backgroundColor: '#FFFFFF',
    padding: 20,
    marginVertical: 8,
    marginHorizontal: 16,
    borderRadius: 5,
    flexDirection: 'column', 
    alignItems: 'flex-start',
  },
  songTitle: {
    fontSize: 18,
    color: '#333',
  },
  rating: {
    flexDirection: 'row',
    marginTop: 5, 
    alignItems: 'center',
  },
  usernameDisplay: {
    fontSize: 20,
    color: '#333',
    textAlign: 'center',
    marginVertical: 10,
  },
  exitButton: {
    backgroundColor: '#d9534f',
    padding: 15,
    borderRadius: 5,
    alignItems: 'center',
    marginHorizontal: 10,
    marginBottom: 10,
  },
});

export default Index;