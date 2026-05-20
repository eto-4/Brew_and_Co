import { useState, useRef, useEffect } from 'react'
import { Send } from 'lucide-react'
import { sendAiMessage } from '../../api/admin'
import { useAuth } from '../../hooks/useAuth'
import { chatStyles, adminLayout } from '../../styles/js/admin'

const INITIAL_MESSAGE = {
  role: 'ai',
  content: 'Hola! Soc el teu assistent de Brew & Co. Puc respondre preguntes sobre vendes, productes i estadístiques. Com puc ajudar-te?',
}

export default function AiChatTab() {
  const { token, user } = useAuth()
  const [messages, setMessages] = useState([INITIAL_MESSAGE])
  const [input, setInput] = useState('')
  const [loading, setLoading] = useState(false)
  const bottomRef = useRef(null)

  useEffect(() => {
    bottomRef.current?.scrollIntoView({ behavior: 'smooth' })
  }, [messages, loading])

  async function handleSend() {
    if (!input.trim() || loading) return
    const userMsg = { role: 'user', content: input.trim() }
    setMessages(prev => [...prev, userMsg])
    setInput('')
    setLoading(true)
    try {
      const res = await sendAiMessage(token, userMsg.content)
      setMessages(prev => [...prev, { role: 'ai', content: res.message }])
    } catch (err) {
      setMessages(prev => [...prev, {
        role: 'ai',
        content: 'Error en la connexió amb la IA. Torna-ho a intentar.',
        error: err.message
      }])
    } finally {
      setLoading(false)
    }
  }

  function handleKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault()
      handleSend()
    }
  }

  const userInitials = user?.nom?.slice(0, 2).toUpperCase() || 'TU'

  return (
    <div>
      <h2 className={adminLayout.sectionTitle}>Assistent IA</h2>
      <div className={chatStyles.container}>
        <div className={chatStyles.header}>
          <div className={chatStyles.headerDot} />
          <span className={chatStyles.headerName}>Groq — LLaMA3</span>
        </div>

        <div className={chatStyles.messages}>
          {messages.map((msg, idx) => (
            <div key={idx} className={msg.role === 'ai' ? chatStyles.rowAi : chatStyles.rowUser}>
              <div className={msg.role === 'ai' ? chatStyles.avatarAi : chatStyles.avatarUser}>
                {msg.role === 'ai' ? 'AI' : userInitials}
              </div>
              <div className={msg.role === 'ai' ? chatStyles.bubbleAi : chatStyles.bubbleUser}>
                {msg.content}
              </div>
            </div>
          ))}
          {loading && (
            <div className={chatStyles.rowAi}>
              <div className={chatStyles.avatarAi}>AI</div>
              <span className={chatStyles.typing}>Escrivint...</span>
            </div>
          )}
          <div ref={bottomRef} />
        </div>

        <div className={chatStyles.footer}>
          <textarea
            className={chatStyles.input}
            rows={1}
            placeholder="Escriu un missatge..."
            value={input}
            onChange={e => setInput(e.target.value)}
            onKeyDown={handleKeyDown}
          />
          <button
            className={chatStyles.sendBtn}
            onClick={handleSend}
            disabled={loading || !input.trim()}
          >
            <Send size={18} />
          </button>
        </div>
      </div>
    </div>
  )
}