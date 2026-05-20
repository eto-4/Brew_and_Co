import { useState } from "react"
import { AuthContext } from "./AuthContext"

function getInitialUser() {
  try {
    const saved = localStorage.getItem('user')
    return saved ? JSON.parse(saved) : null
  } catch {
    return null
  }
}

function getInitialToken() {
  return localStorage.getItem('token') || null
}

export function AuthProvider({ children }) {
  const [user, setUser] = useState(getInitialUser)
  const [token, setToken] = useState(getInitialToken)

  function saveSession(userData, userToken) {
    setUser(userData)
    setToken(userToken)
    localStorage.setItem('token', userToken)
    localStorage.setItem('user', JSON.stringify(userData))
  }

  function clearSession() {
    setUser(null)
    setToken(null)
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  return (
    <AuthContext.Provider value={{ user, token, saveSession, clearSession }}>
      {children}
    </AuthContext.Provider>
  )
}