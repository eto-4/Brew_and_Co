import { useEffect, useRef } from 'react'
import { getOrder } from '../api/orders'

export function useOrderPolling(token, orderId, onUpdate, interval = 7000) {
  const ref = useRef(null)
  const onUpdateRef = useRef(onUpdate)
  
  useEffect(() => {
    onUpdateRef.current = onUpdate
  }, [onUpdate])

  useEffect(() => {
    if (!orderId) return

    async function poll() {
      try {
        const updated = await getOrder(token, orderId)
        onUpdateRef.current(updated)
      } catch (err) {
        console.error(err)
      }
    }

    poll()
    ref.current = setInterval(poll, interval)

    return () => clearInterval(ref.current)
  }, [token, orderId, interval])
}