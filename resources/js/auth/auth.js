export function isAuthenticated() {
    // Check if the user is logged in by verifying if the token exists in localStorage
    const token = localStorage.getItem('token');
    return !!token; // Return true if token exists, false otherwise
  }