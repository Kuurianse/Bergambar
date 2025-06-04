import type { Metadata } from 'next'
import './globals.css'
import { AuthProvider } from '@/context/AuthContext' // Import AuthProvider

export const metadata: Metadata = {
  title: 'Admin Panel', // Updated title
  description: 'Admin Panel for Bergambar Project', // Updated description
  // generator: 'v0.dev', // Optional: remove or keep
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html lang="en">
      <body>
        <AuthProvider> {/* Wrap children with AuthProvider */}
          {children}
        </AuthProvider>
      </body>
    </html>
  )
}
