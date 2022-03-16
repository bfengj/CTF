package org.terracotta.quartz;

import java.util.concurrent.TimeUnit;
import java.util.concurrent.locks.Condition;
import org.terracotta.toolkit.ToolkitFeatureTypeInternal;
import org.terracotta.toolkit.atomic.ToolkitTransaction;
import org.terracotta.toolkit.atomic.ToolkitTransactionController;
import org.terracotta.toolkit.atomic.ToolkitTransactionType;
import org.terracotta.toolkit.concurrent.locks.ToolkitLock;
import org.terracotta.toolkit.concurrent.locks.ToolkitLockType;
import org.terracotta.toolkit.internal.ToolkitInternal;
import org.terracotta.toolkit.rejoin.RejoinException;

class TransactionControllingLock implements ToolkitLock {
   private final ThreadLocal<TransactionControllingLock.HoldState> threadState = new ThreadLocal<TransactionControllingLock.HoldState>() {
      protected TransactionControllingLock.HoldState initialValue() {
         return TransactionControllingLock.this.new HoldState();
      }
   };
   private final ToolkitTransactionController txnController;
   private final ToolkitTransactionType txnType;
   private final ToolkitLock delegate;

   public TransactionControllingLock(ToolkitInternal toolkit, ToolkitLock lock, ToolkitTransactionType txnType) {
      this.txnController = (ToolkitTransactionController)toolkit.getFeature(ToolkitFeatureTypeInternal.TRANSACTION);
      this.txnType = txnType;
      this.delegate = lock;
   }

   public Condition newCondition() throws UnsupportedOperationException {
      throw new UnsupportedOperationException();
   }

   public Condition getCondition() {
      throw new UnsupportedOperationException();
   }

   public ToolkitLockType getLockType() {
      return this.delegate.getLockType();
   }

   public boolean isHeldByCurrentThread() {
      return this.delegate.isHeldByCurrentThread();
   }

   public void lock() {
      this.delegate.lock();

      try {
         ((TransactionControllingLock.HoldState)this.threadState.get()).lock();
      } catch (RejoinException var2) {
         this.delegate.unlock();
      }

   }

   public void lockInterruptibly() throws InterruptedException {
      this.delegate.lockInterruptibly();

      try {
         ((TransactionControllingLock.HoldState)this.threadState.get()).lock();
      } catch (RejoinException var2) {
         this.delegate.unlock();
      }

   }

   public boolean tryLock() {
      if (this.delegate.tryLock()) {
         try {
            ((TransactionControllingLock.HoldState)this.threadState.get()).lock();
         } catch (RejoinException var2) {
            this.delegate.unlock();
         }

         return true;
      } else {
         return false;
      }
   }

   public boolean tryLock(long time, TimeUnit unit) throws InterruptedException {
      if (this.delegate.tryLock(time, unit)) {
         try {
            ((TransactionControllingLock.HoldState)this.threadState.get()).lock();
         } catch (RejoinException var5) {
            this.delegate.unlock();
         }

         return true;
      } else {
         return false;
      }
   }

   public void unlock() {
      try {
         ((TransactionControllingLock.HoldState)this.threadState.get()).unlock();
      } finally {
         this.delegate.unlock();
      }

   }

   public String getName() {
      return this.delegate.getName();
   }

   class HoldState {
      private ToolkitTransaction txnHandle;
      private int holdCount = 0;

      void lock() {
         if (this.holdCount++ == 0) {
            if (this.txnHandle != null) {
               throw new AssertionError();
            }

            this.txnHandle = TransactionControllingLock.this.txnController.beginTransaction(TransactionControllingLock.this.txnType);
         }

      }

      void unlock() {
         if (--this.holdCount <= 0) {
            try {
               this.txnHandle.commit();
            } catch (RejoinException var5) {
               throw new RejoinException("Exception caught during commit, transaction may or may not have committed.", var5);
            } finally {
               TransactionControllingLock.this.threadState.remove();
            }
         }

      }
   }
}
